<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\UserPlans;
use App\Models\Categories;
use App\Models\Setting;
use App\Models\Countries;
use App\Models\BankSetting;
use App\Models\CartItems;
use App\Models\Cart;
use App\Models\OrderItems;
use App\Models\Rating;
use Illuminate\Http\Request;
use Mail;
use JWTAuth;
use DB;
use Stripe;
use Helpers;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Orders;
use App\Models\Transactions;
use App\Models\Coupons;
use App\Models\CouponAppliences;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="api.host.com",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="MNG Apis",
 *         description="Api description...",
 *         termsOfService="",
 *         @SWG\Contact(
 *             email="@.com"
 *         ),
 *         @SWG\License(
 *             name="Private License",
 *             url="URL to the license"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about my website",
 *         url="http..."
 *     )
 * )
 */


class CartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    /**
     * @OA\Get(
     ** path="/api/addtocart/{id}",
     *   tags={"User"},
     *   summary="add to cart",
     *   operationId="addtocart",
     *
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/

    public function add($id)
    {
        try{
            if(empty($id)){
                $response['code'] = 404;
                $response['status'] = 'Fail';
                $response['message'] = "Selected plan is not available.";  
                return response()->json($response);
            }
            
            $user = JWTAuth::parseToken()->authenticate();

            $cart = Cart::where('user_id', $user['id'])->first();
            if(empty($cart)){
                $cart = Cart::create(['user_id'=>$user['id']]);
            }

            $isExists = CartItems::where('cart_id', $cart['id'])->where('user_plan_id',$id )->count();
            if($isExists){
                $response['code'] = 404;
                $response['status'] = 'Fail';
                $response['message'] = "Seleted plan already added in your cart. Please try with another plan."; 
                return response()->json($response);
            }

            $cartItem = [
                'cart_id' => $cart['id'],
                'user_plan_id' => $id
            ];
            if(CartItems::create($cartItem)){
                Cart::rfs();
                $response['code'] = 200;
                $response['status'] = "Success";
                $response['message'] = "Plan added to your cart.";
                return response()->json($response);
            }
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => 'Seleted Plan cannot be added to your cart. Please try with another plan.']);
        }
    } 


    /**
     * @OA\Get(
     ** path="/api/mycart",
     *   tags={"User"},
     *   summary="My Cart",
     *   operationId="mycart",
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/
    public function myCart()
    {
            $user = JWTAuth::parseToken()->authenticate();

            $cart = Cart::my($user['id']);
            Cart::rfs($user['id']);
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['message'] = "Cart details";

            if(count($cart['items'])){
                foreach ($cart['items'] as $key => $item) {
                    if(is_file(public_path("/media/users").'/'.@$item['userPlan']['influencer']['image'])){
                        $cart['items'][$key]['userPlan']['influencer']['image'] = asset('media/users/'.@$item['userPlan']['influencer']['image']);
                    }else{
                        $cart['items'][$key]['user_image'] = asset('media/users/blank.png');
                    }   
                }
            }

            $cards=[];
            try{
                $bankDetails = BankSetting::where('user_id', $user['id'])->first();
                if(!empty($bankDetails)){
                    $settings = Setting::first();
                    $stripe = Stripe::make($settings['stripe_sk']);

                    $customer = $stripe->customers()->find($bankDetails['customer_id']);
                    $cards = $customer['sources']['data'];
                }
            }catch (\Exception $ex) {
                // return response()->json(['code'=>200,'status'=>'Fail','message' => 'Seleted Plan cannot be added to your cart. Please try with another plan.']);
            }

            $response['data'] = $cart;
            $response['cards'] = $cards;
            return response()->json($response);
            
    }


    /**
     * @OA\Post(
     ** path="/api/deleteItem",
     *   tags={"User"},
     *   summary="Delete Cart Item",
     *   operationId="deleteItem",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="item_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/

    public function deleteItem(Request $request){
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'item_id' => 'required',
        ]);
    
        if ($validator->fails()) {
            $response['code'] = 402;
            $response['status'] = "Fail";
            $response['message'] = $validator->errors()->first();
            return response()->json($response);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $item = CartItems::where('id', @$inputs['item_id'])->first();
        if($item){
            $response['code'] = 402;
            $response['status'] = "Fail";
            $response['message'] = "Item is not available in your cart";
            return response()->json($response);
        }
        $item->delete();
        Cart::rfs($user['id']);
        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = "Item deleted from your cart";
        return response()->json($response);

    }


    /**
     * @OA\Post(
     ** path="/api/placeorder",
     *   tags={"User"},
     *   summary="Place Order",
     *   operationId="placeorder",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="text"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="ccnum",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="expiry",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="cvc",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="selected_card",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="city",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="state",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="zip",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="country",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/
    public function placeOrder(Request $request){
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'description' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            // 'ccnum' => 'required_if:selected_card,==,null',
            // 'expiry' => 'required_if:selected_card,==,null',
            // 'cvc' => 'required_if:selected_card,==,null',
        ]);
    
        if ($validator->fails()) {
            $response['code'] = 402;
            $response['status'] = "Fail";
            $response['message'] = $validator->errors()->first();
            return response()->json($response);
        }
        $user = JWTAuth::parseToken()->authenticate();
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

            if(!count($cart->items)){
                return response()->json(['code' => 400, 'status'=> "Fail", 'message'=> "Cart is empty"]);
            }

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

            if(@$inputs['selected_card']){
                $card = $stripe->cards()->find($stripe_customer_id, $inputs['selected_card']);
            }else{
                // == create token
                $token = $stripe->tokens()->create([
                    'card' => [
                        'name'    => $inputs['first_name'] . ' ' . $inputs['last_name'],
                        'number'    => @$inputs['ccnum'],
                        'exp_month' => @$expiry[0] ? (int) $expiry[0] : '',
                        'cvc'       => @$inputs['cvc'],
                        'exp_year'  => @$expiry[1] ? (int) $expiry[1] : '',
                        'address_city' => @$inputs['city'],
                        'address_country' => @$inputs['country'],
                        'address_line1' => @$inputs['address'],
                        'address_zip' => @$inputs['zip'],
                    ],
                ]);
                // update card in user profile
                $card = $stripe->cards()->create($stripe_customer_id, $token['id']);
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

            //$request->session()->put('order_id', $order['id']);
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['message'] = "Order placed successfully";
            $response['order_id'] = $order['id'] ;
            return response()->json($response);
        
        } catch (\Cartalyst\Stripe\Exception\BadRequestException $e) {
            return response()->json(['code' => $e->getCode(), 'status'=> "Fail", 'message'=> $e->getMessage(), 'order_id'=>@$order['id']]);
        } catch (\Cartalyst\Stripe\Exception\UnauthorizedException $e) {
            return response()->json(['code' => $e->getCode(), 'status'=> "Fail", 'message'=> $e->getMessage(), 'order_id'=>@$order['id']]);
        } catch (\Cartalyst\Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['code' => $e->getCode(), 'status'=> "Fail", 'message'=> $e->getMessage(), 'order_id'=>@$order['id']]);
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
            return response()->json(['code' => $e->getCode(), 'status'=> "Fail", 'message'=> $e->getMessage(), 'order_id'=>@$order['id']]);
        } catch (\Cartalyst\Stripe\Exception\ServerErrorException $e) {
            return response()->json(['code' => $e->getCode(), 'status'=> "Fail", 'message'=> $e->getMessage(), 'order_id'=>@$order['id']]);
        } catch (\Cartalyst\Stripe\Exception\NotFoundException $e) {
            return response()->json(['code' => $e->getCode(), 'status'=> "Fail", 'message'=> $e->getMessage(), 'order_id'=>@$order['id']]);
        } catch (\Cartalyst\Stripe\Exception\ServerErrorException $e) {
            return response()->json(['code' => $e->getCode(), 'status'=> "Fail", 'message'=> $e->getMessage(), 'order_id'=>@$order['id']]);
        } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            return response()->json(['code' => $e->getCode(), 'status'=> "Fail", 'message'=> $e->getMessage(), 'order_id'=>@$order['id']]);
        } catch (\Cartalyst\Stripe\Exception\Handler $e) {
            return response()->json(['code' => $e->getCode(), 'status'=> "Fail", 'message'=> $e->getMessage(), 'order_id'=>@$order['id']]);
        } 
    
    }

    /**
     * @OA\Post(
     ** path="/api/addRatings",
     *   tags={"User"},
     *   summary="Add rating to influencer",
     *   operationId="addRatings",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="item_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="review",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="rating",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/
    public function addRatings(Request $request){
        $inputs = $request->all();
        $user = JWTAuth::parseToken()->authenticate();
        $item = OrderItems::where('id', $inputs['item_id'])->with('userPlan')->first();
        $influencer = $item->userPlan->user_id;
        $Rating = Rating::where('user_id',$user['id'])->where('item_id',$inputs['item_id'])->where('influencer_id',$influencer)->first();
        if(empty($Rating)){
            $Rating = new Rating();   
        }
        $Rating->order_id = $inputs['item_id'];
        $Rating->review = $inputs['review'];
        $Rating->rating = $inputs['rating'];
        $Rating->influencer_id = $influencer;
        $Rating->user_id = $user['id'];
        $Rating->save();
        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = "Rating saved successfully";
        return response()->json($response);
    }


    /**
     * @OA\Post(
     ** path="/api/apply-coupon",
     *   tags={"User"},
     *   summary="Apply Coupon to cart",
     *   operationId="apply-coupon",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/
    public function applyCoupon(Request $request){
        $user = JWTAuth::parseToken()->authenticate();

        $res = (new \App\Http\Controllers\CartController())->applycoupon($request, $user, true)
        if($res['success']){
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['message'] = $res['message'];
            $response['cart'] = Cart::my($user['id']);
        }

        return response()->json($response);

    }


    /**
     * @OA\Post(
     ** path="/api/remove-coupon",
     *   tags={"User"},
     *   summary="Remove Coupon from cart",
     *   operationId="apply-coupon",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="coupon_applience_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/
    public function removeCoupon(Request $request){

        $id = $request->get('coupon_applience_id');
        if(!$id){
            return response()->json(['success'=>false, 'message'=> "Please send coupon appllience id"]);
        }

        $coupon = CouponAppliences::where('id', $id )->first();
        $coupon->delete();
        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = "Coupon removed successfully.";
        $response['cart'] = Cart::my($user['id']);
        return response()->json($response);

    }




}
