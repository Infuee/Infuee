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
use File;
use App;
use Helpers;
use App\Models\Cart;
use App\Models\CartItems;
use App\Models\Setting;
use App\Models\Countries;
use App\Models\BankSetting;
use App\Models\Transactions;
use App\Models\UserWalletTransaction;
use Stripe;
use App\Models\Coupons;
use App\Models\UserWallet;
use App\Models\CouponAppliences;
use Illuminate\Support\Facades\Storage;
use carbon\Carbon;

class CartController extends Controller
{
    public function add($id)
    {

        $id = (int) Helpers::decrypt($id);
        if(!$id){
            return redirect()->back()->with(['error' => "Selected plan is not available"]);
        }
        
        $user = auth()->user();

        $cart = Cart::where('user_id', $user['id'])->first();
        if(empty($cart)){
            $cart = Cart::create(['user_id'=>$user['id']]);
        }

        $isExists = CartItems::where('cart_id', $cart['id'])->where('user_plan_id',$id )->count();
        if($isExists){
            return redirect('/cart');//->back()->with(['error' => "Seleted plan already added in your cart. Please try with another plan."]);
        }

        $cartItem = [
            'cart_id' => $cart['id'],
            'user_plan_id' => $id
        ];
        if(CartItems::create($cartItem)){
            Cart::rfs();
            return redirect('/cart')->with(['alert' => "Plan added to your cart."]);
        }
        return redirect()->back()->with(['error' => "Seleted Plan cannot be added to your cart. Please try with another plan."]);
    } 

    public function cart(){
        $page_title = 'Cart Items';
        $page_description = 'Cart Items';
        $user = auth()->user();
        $bankDetails = BankSetting::where('user_id', $user['id'])->first();
        $cards=[];
        
        $fingerPrints = [];
        if(!empty($bankDetails) && $bankDetails['customer_id'] ){
            $settings = Setting::first();
            $stripe = Stripe::make($settings['stripe_sk']);

            $customer = $stripe->customers()->find($bankDetails['customer_id']);
            $cards_ = $customer['sources']['data'];
            if(count($cards_)){
                foreach($cards_ as $card){
                    if(!in_array($card['fingerprint'] , $fingerPrints)){
                        $cards[] = $card ;
                        $fingerPrints[] = $card['fingerprint'] ;
                    }
                }
            }
        }


        $cart = Cart::my();
        return view('user.cart', compact('page_title', 'page_description', 'user', 'cart', 'cards'));
    } 

    public function remove($id){
        $id = (int) Helpers::decrypt($id);
        if(!$id){
            return response()->json(['success' => false, 'message'=>"Item is not available."]);
        }

        if(CartItems::where('id', $id)->delete()){
            Cart::rfs();
            return response()->json(['success' => true,'message'=>"Item removed from cart successfully."]);
        }
        return response()->json(['success' => false, 'message'=>"Item is not available."]);
    }

    public function removecard(Request $request){
        $user = auth()->user();
        $settings = Setting::first();
        $bankDetails = BankSetting::where('user_id', $user['id'])->first();
        $stripe = Stripe::make($settings['stripe_sk']);
        $card = $stripe->cards()->delete($bankDetails['customer_id'], $request->get('card_id'));
        if($card['deleted']){
            return response()->json(['success' => true,'message'=>"Card removed successfully"]);
        }
        return response()->json(['success' => false, 'message'=>"Card not available"]);
    }

    public function placeorder(Request $request){
        $inputs = $request->all();
        //dd($inputs);

        $validator = Validator::make($inputs, [
            'description' => 'required',
            'caption' => 'required',
            'image_video' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'ccnum' => 'required_if:selected_card,==,null',
            'expiry' => 'required_if:selected_card,==,null',
            'cvc' => 'required_if:selected_card,==,null',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $user = auth()->user();
        $bankDetails = BankSetting::where('user_id', $user['id'])->first();
        if(empty($bankDetails)){
            $bankDetails = new BankSetting();
            $bankDetails->user_id = $user['id'];
        }
        try {

            $settings = Setting::first();

            $country = Countries::where('id', $user['country_code'])->first();

            $stripe = Stripe::make($settings['stripe_sk']);
            $cart = Cart::my();

            if(@$bankDetails['customer_id']){
                $stripe_customer_id = $bankDetails['customer_id'];
            }else{
                $customer = $stripe->customers()->create([
                    'email' => $user->email,
                    'name' => $inputs['first_name'] . ' ' . $inputs['last_name'],
                    'metadata' => [
                        'dob' => @$user['date_of_birth']
                    ],
                ]);
                
                $bankDetails->customer_id = $customer['id'];
                $stripe_customer_id = $customer['id'];
                $bankDetails->save();
            }

            $expiry = explode('/', $inputs['expiry']);

            if(@$inputs['s_card']){
                $card = $stripe->cards()->find($stripe_customer_id, $inputs['s_card']);
            }else{
                // == create token
                $token = $stripe->tokens()->create([
                    'card' => [
                        'name'    => $inputs['first_name'] . ' ' . $inputs['last_name'],
                        'number'    => $inputs['ccnum'],
                        'exp_month' => (int) $expiry[0],
                        'cvc'       => $inputs['cvc'],
                        'exp_year'  => (int) $expiry[1],
                        'address_city' => $inputs['city'],
                        'address_country' => $inputs['country'],
                        'address_line1' => $inputs['address'],
                        'address_zip' => @$inputs['zip'],
                    ],
                ]);
                // update card in user profile
                $card = $stripe->cards()->create($stripe_customer_id, $token['id']);
            }
             $uploadedvideoimagefile = $request->file('image_video');

             if ($request->has('image_video') && !empty($request->file('image_video'))) {
               
            $directory = 'uploads/job';
            if (! File::exists(public_path().'/'.$directory.'/')) {
                File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
            }
            $filename = preg_replace('/\..+$/', '', $uploadedvideoimagefile->getClientOriginalName()).time().'.'.$uploadedvideoimagefile->getClientOriginalExtension();
            $destinationPath = public_path($directory);
            $uploadedvideoimagefile->move($destinationPath, $filename);
            //$job->image_video = $filename ;
        }
          
            $rawOrder = [
                'user_id' => $user['id'], 
                'description' => $inputs['description'],
                'billing_first_name' => $inputs['first_name'], 
                'billing_last_name' => $inputs['last_name'], 
                'address' => $inputs['address'], 
                'city' => $inputs['city'],
                'state'=> $inputs['state'],
                'zipcode'=> $inputs['zip'], 
                'country'=> $inputs['country'],
                'coupon_id'=> @$inputs['coupon_id'],
                'total' => $cart->total(),
                'discount_price' => $cart->discountAmount(),
                'status' => Orders::STATUS_UNPAID,
                'order_id' => mt_rand(10000000, 99999999)
            ];
            


            if($order = Orders::create($rawOrder)){
               

                $email = $user;

                /*\Mail::send('email.order_place', $confirmed = array('user_info'=>$user), function($message ) use ($email){
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Order Placed successfully')
                    ->to($email);
                });*/

                // echo '<pre>';print_r($cart->items); die;

                
            }
            Orders::where(['id'=> $order['id']])
           ->update(['caption' => $inputs['caption'],'image_video' => @$filename]);
           
            $charge = $stripe->paymentIntents()->create([
                'amount' => $cart->grandTotal(),
                'currency' => 'INR',
                'customer' => $stripe_customer_id,
                'payment_method' => @$card['id']?:0,
                'payment_method_types' => [
                    'card',
                ],
                'confirm' => true,
                'metadata' => [
                    'orderId' => $order['id'],
                ],
            ]);

            $raw = [
                'order_id' => $order['id'],
                'user_id' => $user['id'],
                'transaction_no' => $charge['id'],
                'transaction_time' => date('Y-m-d H:i:s'),
                'type' => 'debit',
                'amount' =>  $cart->grandTotal(),
                'commision' => 0,
                'status' => Transactions::STATUS_UNPAID
            ];

            $order_insert_id = Transactions::create($raw)->id;
            $cart_items = $cart->items;
            // echo '<pre>';print_r($cart->items);die;
            foreach ($cart->items as $key => $item) {
                $raw = [
                    'order_id' => $order['id'],
                    'user_plan_id' => $item['userPlan']['id'],
                    'price' => $item['userPlan']['price'],
                    'status' => 0
                ];
                OrderItems::create($raw);

                /*$influencer = User::where('id', $item['userPlan']['user_id'])->first();
                //Generate pdf
                $Transaction = Transactions::with('user','order','order.order_items','order.order_items.userPlan','order.order_items.userPlan.allPlan','order.order_items.userPlan.allPlan.allCategory')->where('id',$insert_id)->first();
                $html = view('admin.transactions.pdf',compact('Transaction'))->render();
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML($html);
                $influencer_id = $influencer['id'];
                Storage::put('public/pdf/order/'.$order['id'].'/influencers/'.$influencer_id.'/invoice.pdf', $pdf->output());
                // return $pdf->stream('invoice.pdf');
                // return $pdf->download('invoice.pdf');


                $email = $influencer['email'];
                $email = "nikhil@yopmail.com";
                $pathToFile = Storage::path('public/pdf/order/'.$order['id'].'/influencers/'.$influencer_id.'/invoice.pdf');
                // echo $pathToFile; die;
                \Mail::send('email.order_place', $confirmed = array('user_info'=>$user, 'influencer'=>$influencer), function($message ) use ($email,$pathToFile){
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Order Recieved')
                    ->to($email)
                    ->attach($pathToFile);
                });*/
            }
            $totalComm = ($settings->commission / 100) * $cart->total();
            $adminswalletID = \App\Models\UserWallet::where('admin_id', '1')->first();
            if(empty($adminswalletID)){
                $adminswalletID = \App\Models\UserWallet::create(['admin_id' => '1']);
            }
            $wallatTransaction = array(
                   'wallet_id' => $adminswalletID['id'], 
                   'amount' => $cart->total(), 
                   'transaction_type' => '2', 
                   'description' => 'Commission Recieved from cart payment', 
                   'transaction_id' => $charge['id'], 
                   'created_by' => $user['id'], 
                   'commission' => $totalComm ,
                   'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 
                   'updated_at' => Carbon::now()->format('Y-m-d H:i:s'), 
            );
            UserWalletTransaction::insertGetId($wallatTransaction);
            $getWalletAmount = UserWallet::select('amount')->where('admin_id', '1')->first(); 
            $commissionAmount = $totalComm + $getWalletAmount['amount'];
            UserWallet::where('admin_id', '1')->update(['amount' => $commissionAmount]);


            //Generate order transaction
            /*$Transaction = Transactions::where('id',$order_insert_id)->first();
            $html = view('admin.transactions.pdf',compact('Transaction'),["cart_items"=>$cart_items,"order"=>$order,"rawOrder"=>$rawOrder,"transaction_number"=>$charge['id']])->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($html);
            //Create path

            $path = 'pdf/order/'.$order['id']; 
            if(!Storage::disk('public_uploads')->put($path.'/invoice.pdf', $pdf->output())) {
                return false;
            }*/
            // Storage::put('public/pdf/order/'.$order['id'].'/invoice.pdf', $pdf->output());

            $order->stripe_charge_id = $charge['id'];
            $order->save();
            if(@$cart['coupon']){
                $coupon = $cart['coupon'];
                $coupon->is_redeemed = 1;
                $coupon->save();
            }
            $cart->empty();
            // $this->sendPdf($order['id']);
            $request->session()->put('order_id', $order['id']);
            return redirect()->back()->with(['success' => 'Order placed successfully','order_id'=>$order['id']]);

        } catch (\Cartalyst\Stripe\Exception\BadRequestException $e) {
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
        } catch (\Cartalyst\Stripe\Exception\UnauthorizedException $e) {
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
        } catch (\Cartalyst\Stripe\Exception\InvalidRequestException $e) {
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
        } catch (\Cartalyst\Stripe\Exception\ServerErrorException $e) {
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
        } catch (\Cartalyst\Stripe\Exception\NotFoundException $e) {
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
        } catch (\Cartalyst\Stripe\Exception\ServerErrorException $e) {
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
        } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
        } catch (\Cartalyst\Stripe\Exception\Handler $e) {
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
        } 
    
    }

    public function sendPdf($order_id =''){
        $user = auth()->user();
        $Transactions = Transactions::with(['user','order','order.order_items','order.order_items.userPlan','order.order_items.userPlan.allPlan', 'order.order_items.userPlan.allUser', 'order.order_items.userPlan.allPlan.allCategory'])->where('order_id',$order_id)->get();
        
        foreach($Transactions AS $Transaction){
            $user = @$Transaction['user'];
            $trans = Transactions::where('id',$Transaction->id)->first();
            $trans->invoice = 1;
            $trans->save();
            $html = view('admin.transactions.pdf',compact('Transaction'))->render();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($html);
            $path = 'pdf/order/'.$Transaction->order_id; 
            if(!Storage::disk('public_uploads')->put($path.'/invoice.pdf', $pdf->output())) {
                continue;
            }
           
            $pathToFile = public_path().'/uploads/'.$path.'/invoice.pdf';
            $email = @$user['email'];
            \Mail::send('email.order_place', $confirmed = array('user_info'=>$user), function($message ) use ($email,$pathToFile){
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->subject('Order Recieved')
                ->to($email)
                ->attach($pathToFile);
            });

            $plan = $Transaction['order']['order_items'][0]['userPlan'];
            $influencer = $plan['allUser'];
            $plan = $plan['allPlan'];

            \Mail::send('email.order_recieved', $confirmed = array('user_info'=>$user, 'plan'=>$plan, 'influencer'=>$influencer), function($message ) use ($influencer,$pathToFile){
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->subject('Order Recieved')
                ->to($influencer['email']);
            });

        }



        
    }

    public function applycoupon(Request $request, $user = false, $fromAPI = false){

        $code = $request->get('code');
        
        if(!$user){
            $user = auth()->user();
        }    

        $coupon = Coupons::where('code', $code)->first();
        if(empty($coupon)){
            $resp = ['success'=>false, 'message'=> "Coupon is not applicable."];
            if($fromAPI){
                return $resp;
            }
            return response()->json($resp);
        }
        $cart = Cart::where('user_id', $user->id)->first();
        $total = $cart->total();
        
        if(strtotime('now')>strtotime($coupon['expiry_date'])){
            $resp = ['success'=>false, 'message'=> "This coupon is expired. Please try with another coupon"];
            if($fromAPI){
                return $resp;
            }
            return response()->json($resp);
        }
        
        $isApplied = CouponAppliences::where(['cart_id'=> $cart['id'], 'coupon_id'=> $coupon['id']])->count();
        if($isApplied){
            $resp = ['success'=>false, 'message'=> "You have already used this coupon."];
            if($fromAPI){
                return $resp;
            }
            return response()->json($resp);
        }


        if($total < $coupon['min_price']){
            $resp = ['success'=>false, 'message'=> "Coupon is not applicable on your cart."];
            if($fromAPI){
                return $resp;
            }
            return response()->json($resp);
        }

        if($coupon['type'] == 'percentage'){
            $discount = ($coupon['discount'] / 100) * $total;
            if($discount > $coupon['max_price']){
                $discount = $coupon['max_price'];
            }
        }
        if($coupon['type'] == 'flat'){
            $discount = $coupon['discount'] ;
            if($discount > $coupon['max_price']){
                $discount = $coupon['max_price'];
            }
        }
        
        $apply = new CouponAppliences();
        $apply->cart_id = $cart['id'];
        $apply->user_id = $user['id'];
        $apply->coupon_id = $coupon['id'];
        $apply->amount = $discount;
        $apply->is_redeemed = 0 ;
        $apply->save();
        
        $resp = ['success'=>true, 'message'=> "Coupon is applied successfully."];
        if($fromAPI){
            return $resp;
        }
        return response()->json($resp);

    }

    public function removecoupon(Request $request){

        $id = (int) Helpers::decrypt($request->get('coupon'));
        if(!$id){
            return response()->json(['success'=>false, 'message'=> "Coupon not available"]);
        }

        $coupon = CouponAppliences::where('id', $id )->first();
        $coupon->delete();
        return response()->json(['success'=>true, 'message'=> "Coupon removed successfully."]);

    }

}
