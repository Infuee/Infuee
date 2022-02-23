<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Admin;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Countries;
use App\User;
use App\Models\PlanCategories;
use App\Models\Transactions;
use App\Models\Plans;
use App\Models\Setting;
use DB;
use App;
use Stripe;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Manage Transactions';
        $page_description = 'Manage Transactions';
        $user_type = $request->get('user_type');
        /*if(!isset($user_type)){
            $user_type = 2;
        }*/
        $start = 0;

        
        $query = Transactions::with('user')->with('order','order.order_items','order.order_items.userPlan','order.order_items.userPlan.allPlan')
        ->whereHas('user', function($q) use ($user_type){
            if(isset($user_type)){
                $q->where('type', '=', $user_type);
            }
        });        
        if($payment_type = $request->get('payment_type')){
            $query = $query->where('type', 'LIKE', $payment_type);
        }
        if($payment_status = $request->get('payment_status')){
            switch($payment_status){
                case 'paid':
                    $status = 1;
                break;
                case 'pending':
                    $status = 0;
                break;
                case 'fail':
                    $status = 2;
                break;
            }
            $query = $query->where('status', '=', $status);
        }
        // echo '<pre>';print_r($query->get()); die; 
        if($request->ajax()){
            return datatables()->of($query->latest()->get())
            ->addIndexColumn()
            ->addColumn('name', function ($transaction) {
                return @$transaction->user->first_name . ' '. @$transaction->user->last_name;
            })
            ->addColumn('total', function ($transaction) {
                return env('CURRENCY').@$transaction->order->total;
            })
            ->addColumn('order_id', function ($transaction) {
                return @$transaction->order->order_id;
            })
            ->addColumn('discount', function ($transaction) {
                return env('CURRENCY').@$transaction->order->discount_price;
            })
            ->addColumn('type_c', function ($transaction) {
               
                if($transaction->user->type == 2){
                    $type = 'Influencer';
                }else{
                    $type = 'User';
                }
                return $type;
            })
            ->addColumn('commission_c', function ($transaction) {
                
                if($transaction->user->type == 2){
                    $commission = env('CURRENCY').$transaction->commision;                    
                }else{
                    $commission = '--';
                }
                return $commission;
            })
            ->addColumn('influencer_paid_amount', function ($transaction) {
                  
                if($transaction->user->type == 2){
                    $amount = env('CURRENCY').$transaction->amount;                    
                }else{
                    $amount = '--';
                }            
                return $amount;
            })
            ->addColumn('action', function ($data) {
                return @$data['id'];
            })
            ->make(true);
        }  
        
        return view('admin.transactions.list', compact('page_title', 'page_description', 'user', 'inputs'));
    }

    public function downloadPdf($id){
        $settings = Setting::first();
        $stripe = new Stripe();
        $stripe = Stripe::make($settings['stripe_sk']);
        $Transaction = Transactions::with('user','order','order.order_items','order.order_items.userPlan','order.order_items.userPlan.allPlan','order.order_items.userPlan.allPlan.allCategory')->where('id',$id)->first();
        // echo '<pre>';print_r($Transaction); die;  
        $transaction_no = $Transaction->transaction_no;
        //$paymentIntent = $stripe->paymentIntents()->find($transaction_no);
        // echo '<pre>';print_r($paymentIntent); die;
        $html = view('admin.transactions.pdf',compact('Transaction'))->render();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($html);
        // return $pdf->stream();
        // return $pdf->download($Transaction->user->first_name.'.pdf');
        return $pdf->download('invoice.pdf');
    }   
}
