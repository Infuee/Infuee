<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Admin;
use App\Models\Orders;
use App\Models\OrderItems;
use App\User;
use App\Models\PlanCategories;
use App\Models\Categories;
use App\Models\Plans;
use DB;
use Helpers;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Setting;
use App\Models\Countries;
use App\Models\BankSetting;
use Stripe;
use App\Models\UserPlans;
use App\Models\Transactions;


class WebhookController extends Controller
{
    public function transferComplete(Request $request){

    	$settings = Setting::first();
	        
        $stripe = Stripe::make($settings['stripe_sk']);

        $payload = @file_get_contents('php://input');

        $event = json_decode($payload);
        if($event->type == 'transfer.paid' || $event->type == 'transfer.created'){
            return $this->transferPaid($event->data->object);
        }else if($event->type == 'payment_intent.succeeded'){
            return $this->orderPaid($event->data->object);
        }   
        return response()->json(['status'=>true, 'message'=> 'other events', 'type'=>$event->type]);
        
    }

    function transferPaid($obj){
        $transferId = $obj->id ;
        $item = OrderItems::where('transfer_id', $transferId)->first();
        $transaction = Transactions::where('transaction_no', $transferId)->first();
        $transaction->status = Transactions::STATUS_PAID;
        $transaction->save();
        return response()->json(['status'=>true, 'message'=> 'Transactions details updated successfully.']);
    }

    function orderPaid($obj){
        $piId = $obj->id ;
        $order =  Orders::where('stripe_charge_id' ,  $piId)->first();
        $order->status  = Orders::STATUS_PAID;
        $order->save();
        $transaction =  Transactions::where('transaction_no' ,  $piId)->first();
        $transaction->status  = Transactions::STATUS_PAID;
        $transaction->save();
        return response()->json(['status'=>true, 'message'=> 'Transactions details updated successfully.']);
    }

}
