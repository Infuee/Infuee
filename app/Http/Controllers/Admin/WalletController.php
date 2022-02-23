<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use DB;
use App\Models\UserWallet;
use App\Models\Admin;
use App\User;
use App\Models\UserWalletTransaction;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $page_title = 'Manage Transactions';
        $page_description = 'Manage Transactions';
        $user_type = $request->get('user_type');
        $user = Auth::guard('admin')->user();
        $start = 0;
        $query = UserWallet::with(['user'])->get() ;
        
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('user_name', function ($transaction) {
                return @$transaction->user->first_name . ' '. @$transaction->user->last_name;
            })
            ->addColumn('amount', function ($transaction) {
                return '$'.@$transaction->amount;
            })
            ->addColumn('type_c', function ($transaction) {
                if(@$transaction->user->type == 2){
                    $type = 'Influencer';
                }else{
                    $type = 'User';
                }
                return $type;
            })
            ->addColumn('action', function ($data) {

                $html = '<a href="'. url( '/admin/wallet/'.$data['id'].'/transaction' )  .'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">
                    <span class="svg-icon svg-icon-md"><i class="fa fa-tasks"></i>
                    </span>
                </a>';

                return $html;
            })

            ->rawColumns(['action'])
            ->make(true);
        }  
        
        return view('admin.wallet.list', compact('page_title', 'page_description', 'user', 'inputs'));
    }

    public function transactions(Request $request, $wallet_id = false){

        $inputs = $request->all();
        $page_title = 'Manage Transactions';
        $page_description = 'Manage Transactions';
        $user_type = $request->get('user_type');
        $user = Auth::guard('admin')->user();
        $start = 0;
        $adminName = Admin::first();

        $query = UserWalletTransaction::with(['wallet', 'wallet.user'])->get();
       
        if($wallet_id){
            $query = $query->where('wallet_id', $wallet_id);
        }
        $type = $request->get('type') ? : 'user';

        if($request->ajax()){
            if($type == 'user'){
                $userIds = User::where('type', 1)->pluck('id')->toArray();
                $walletIds = UserWallet::whereIn('user_id', $userIds)->pluck('id')->toArray();
            }else if($type == 'influencer'){
                $userIds = User::where('type', 2)->pluck('id')->toArray();
                $walletIds = UserWallet::whereIn('user_id', $userIds)->pluck('id')->toArray();
            }else{
                $walletIds = UserWallet::where('admin_id', '!=', null)->pluck('id')->toArray();
            }

            $query = $query->whereIn('wallet_id', $walletIds);
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('user_name', function ($data) {
                if(!empty($data->wallet->user->first_name)){
                    return @$data->wallet->user->first_name . ' ' . @$data->wallet->user->last_name;
                } else {
                    return 'Admin Super';
                }
            })
            ->addColumn('type', function($data){
                return $data->transaction_type == UserWalletTransaction::TYPE_DABIT ? 'Debit' : 'Credit';
            })
            ->addColumn('amount', function ($transaction) {
                return '$'.@$transaction->amount;
            })
            ->addColumn('created_at', function ($data) {
               return [ date('d F Y', strtotime($data->created_at)) ];
            })
            
            ->make(true);
        }  
        
        return view('admin.wallet.transaction', compact('page_title', 'page_description', 'user', 'inputs', 'type'));
    }

    
  
}
