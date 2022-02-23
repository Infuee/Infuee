<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use DB;
use App\Models\UserWallet;
use App\Models\UserWalletTransaction;
use Illuminate\Support\Facades\Auth;

class CommissionWalletTransaction extends Controller
{
    public function index( Request $request , $wallet_id = false){
        $inputs = $request->all();
        $page_title = 'Manage Transactions';
        $page_description = 'Manage Transactions';
        $user_type = $request->get('user_type');
        $user = Auth::guard('admin')->user();
        $start = 0;
        $adminswalletID = \App\Models\UserWallet::select('id')->where('admin_id', '1')->first();
        $query = UserWalletTransaction::with(['wallet', 'user','influencers'])->where('wallet_id', @$adminswalletID['id']);

        if($wallet_id){
            $query = $query->where('wallet_id', $wallet_id);
        }
        if($request->ajax()){
            return datatables()->of($query->get())
            ->addIndexColumn()
            ->addColumn('user_name', function ($data) {
                return @$data->user->first_name . ' ' . @$data->user->last_name;
            })
            ->addColumn('influencer_id', function ($data) {
                return @$data->influencers->first_name ? @$data->influencers->first_name . ' ' . @$data->influencers->last_name : "NA";
            })
            ->addColumn('job_id', function ($data) {
                return @$data->jobName->title ? : "NA";
            })
            ->addColumn('amount', function ($data) {
                return "$". '' . @$data->amount;
            })
            ->addColumn('commission', function ($data) {
                return "$". '' . @$data->commission;
            })
            ->addColumn('date', function($data){
                return date('d F, Y', strtotime($data->created_at));
            })
            ->addColumn('type', function($data){
                return @$data->transaction_type == UserWalletTransaction::TYPE_DABIT ? 'Debit' : 'Credit';
            })
            ->addColumn('description', function($data){
                return @$data->description;
            })
            
            ->make(true);
        }

        return view('admin.wallet.commission_transaction', compact('page_title', 'page_description', 'user', 'inputs')); 
    }
}
