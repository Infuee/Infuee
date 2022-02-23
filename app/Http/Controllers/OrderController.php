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
use ZipArchive;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Setting;
use App\Models\Countries;
use App\Models\BankSetting;
use Stripe;
use App\Models\UserPlans;
use App\Models\Transactions;


class OrderController extends Controller
{
    public function orders()
    {
        $user = auth()->user();
        
        $userPlans = UserPlans::where('user_id', $user['id'])->pluck('id')->toArray();
        $orderItems = OrderItems::whereIn('user_plan_id', $userPlans)->with(['userPlan', 'userPlan.influencer', 'userPlan.plan', 'order'])->latest()->paginate(10);

        $page_title = "Orders";
        $page_description = "List of orders";

        return view('influencer.orders', compact('page_title', 'page_description', 'user', 'orderItems'));

    } 

    public function accept(Request $request, $id = false){

    	$item = $request->get('item');
    	$item = (int) Helpers::decrypt($item);

        $item = $id ? : $item ;

    	if(!$item){
            return response()->json(['success'=>false, 'message' => "Selected order is not available"]);
        }
        try {
	        $orderItem = OrderItems::where('id', $item)->first();
	        if(empty($orderItem)){
	        	return response()->json(['success'=>false, 'message' => "Selected order is not available"]);
	        }

	        $influencer = auth()->user();
	        $bankDetails = BankSetting::where('user_id', $influencer['id'])->first();
 			
	        if(empty($bankDetails) || !@$bankDetails['account_id']){
	        	return response()->json(['success'=>false, 'message' => "Please complete your bank details before accepting orders."]);
	        }

	        $orderItem->status = OrderItems::STATUS_ACCEPTED;
	        $orderItem->save();

	        $settings = Setting::first();
	        
	        $stripe = Stripe::make($settings['stripe_sk']);
	        
	        $transferAmount = $orderItem->influencerAmount();

	        $transfer = $stripe->transfers()->create([
	            'amount'    => floatval($transferAmount),
	            'currency'  => 'inr',
	            'destination' => @$bankDetails['account_id'],
	            'metadata' => [
	                'item' => $orderItem['id']
	            ],
	        ]);
	       
	       
	        $orderItem->transfer_id = $transfer['id'];
	        $orderItem->save();
	        $raw = [
	        	'order_id' => $orderItem['order_id'],
	        	'order_item_id' => $orderItem['id'],
				'user_id' => $influencer['id'],
				'transaction_no' => $transfer['id'],
				'transaction_time' => date('Y-m-d H:i:s'),
                'amount' => $transferAmount,
                'commision' => $orderItem['price'] - $transferAmount,
				'type' => Transactions::TYPE_CREDIT,
				'status' => Transactions::STATUS_UNPAID
	        ];

            $order = Orders::where('id', $orderItem['order_id'])->first();
            $user = User::where('id', $order['user_id'])->first();

            $email = @$user['email'];

            \Mail::send('email.order_accepted', $confirmed = array('user_info'=>$user, 'influencer'=>$influencer), function($message ) use ($email){
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->subject('Order Accepted')
                ->to($email);
            });

	        if(Transactions::create($raw)){
	        	return response()->json(['success'=>true, 'message' => "Order accepted successfully."]);
	        }
	        return response()->json(['success'=>false, 'message' => "Please complete your bank details before accepting orders."]);
        } catch (\Cartalyst\Stripe\Exception\BadRequestException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'success' => false]);
        } catch (\Cartalyst\Stripe\Exception\UnauthorizedException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'success' => false]);
        } catch (\Cartalyst\Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'success' => false]);
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'success' => false]);
        } catch (\Cartalyst\Stripe\Exception\ServerErrorException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'success' => false]);
        } catch (\Cartalyst\Stripe\Exception\NotFoundException $e) {
            return respons()->json(['message' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'success' => false]);
        } catch (\Cartalyst\Stripe\Exception\ServerErrorException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'success' => false]);
        } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'success' => false]);
        } catch (\Cartalyst\Stripe\Exception\Handler $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'success' => false]);
        }
    }

    public function transactions(Request $request){

    	$user = auth()->user();
    	$transactions = Transactions::where('user_id', $user['id'])->with(['order', 'order.user', 'item', 'item.userPlan.allPlan'])->latest()->paginate(20);
    	$page_title = "Transactions";
        $page_description = "List of Transactions";

        return view('influencer.transactions', compact('page_title', 'page_description', 'user', 'transactions'));
    }

    public function downloadZip(Request $request){
         $order=Orders::where('order_id',$request->item)->first();
       
         $caption=$order->caption;
         $captionFile = storage_path($order->order_id.'.txt') ;

        $fp = fopen($captionFile, 'w');
        fwrite($fp, $order->caption);
        fclose($fp);
        $zip = new ZipArchive;
        $fileName = $order->order_id.'.zip';
        if(empty(@$order->image_video)){
          $filePath = public_path('uploads/job/No_result1636634534.png') ;
        }else{
          $filePath = public_path('uploads/job/'. $order->image_video);
        }
        if ($zip->open(storage_path($fileName), ZipArchive::CREATE) === TRUE)
        {
            $zip->addFile($filePath, basename(@$filePath));
            $zip->addFile($captionFile, basename($captionFile));
            $zip->close();
        }
    
        return response()->download(storage_path($fileName));
    }

}
