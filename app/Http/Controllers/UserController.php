<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentPages;
use App\Models\Categories;
use App\User;
use App\Models\Faq;
use App\Models\FaqCat;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Validator;
use App\Models\InfluencerRequests;
use App\Models\PlanCategories;
use App\Models\Plans;
use App\Models\UserPlans;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use App\Models\Countries;
use App\Models\BankSetting;
use App\Models\Setting;
use App\Models\JobReviews;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Rating;
use App\Models\UserWallet;
use App\Models\UserWalletTransaction;
use App\Models\UserPlatformStats ;
use Helpers;
use Auth;
use PDF;
use DB;
use Redirect;
use Carbon\Carbon;
use Stripe;
use Cartalyst\Stripe\Exception\BadRequestException;
use Cartalyst\Stripe\Exception\UnauthorizedException;
use Cartalyst\Stripe\Exception\InvalidRequestException;
use Cartalyst\Stripe\Exception\NotFoundException;
use Cartalyst\Stripe\Exception\CardErrorException;
use Cartalyst\Stripe\Exception\ServerErrorException;
use Cartalyst\Stripe\Exception\MissingParameterException; 

class UserController extends Controller
{
    public function storeInfluencer(Request $request){
        $inputs = $request->all();

        $user = auth()->user();

        if($user){
            $field = $request->get('field');
            $val = $request->get('val');
            if($field == 'date_of_bith'){

                if(strtotime($val)){
                    $user->$field = date('Y-m-d', strtotime($val) );
                }else{
                    return response()->json(['status'=>'danger', 'message'=>'Date of birth is not well formetted. Please enter a valid date of birth.']);
                }

            }elseif($field == 'password'){
                $user->$field = Hash::make($val);
            }else{
                $user->$field = $val;
            }

            $user->save();

            return response()->json(['status'=>'success', 'message'=>'Details saved successfully.']);

        }

        return response()->json(['status'=>'danger', 'message'=>'Something went wrong.']);

    }

    public function myProfile(){
        $user = auth()->user();
        $page_title = env('APP_NAME').' | '.$user['username'];
        $page_description = $user['username'].' profile';

        if($user['type'] == 2){
            $rating = Helpers::get_ratings_average($user['id']);
        }
        //$query = User::where('status', User::STATUS_ACTIVE)->where('type', User::TYPE_INFLUENCER);
        $query = User::withPlans()->where('status', User::STATUS_ACTIVE)->where('account_verified', 1)->where('type', User::TYPE_INFLUENCER);
        if($user = auth()->user()){
            $query = $query->where('id', '!=', $user['id']);
        }
        $influencers = $query->paginate(4);

        if($user->isUser()){
            $myprofile = false;
            $orders = [];
            $orders = Orders::where('user_id', $user['id'])->pluck('id')->toArray();
            $orderItems = OrderItems::whereIn('order_id', $orders)->with(['userPlan', 'userPlan.influencer', 'userPlan.plan', 'order'])->latest()->paginate(10);
            return view('user.profile', compact('page_title', 'page_description','user', 'influencers', 'myprofile', 'orderItems'));
        }

        $categories = PlanCategories::where('status', PlanCategories::STATUS_ACTIVE)->with(['plans'=>function($q){
            $q->where('status', Plans::STATUS_ACTIVE);
        }])->get();
        $platforms = UserPlatformStats::where('user_id', $user['id'])->pluck('platform_id')->toArray();
        $authUserId=Auth::user()->id;
        $authUser=User::where('id',$authUserId)->orWhere('type',2)->first();
        $myprofile = true;
        $WorkHistory=DB::table('jobs_reviews')
                    ->join('users','users.id','=','jobs_reviews.rated_from')
                     ->where(['rated_by' => $authUserId])
                    ->select('jobs_reviews.rated_from','jobs_reviews.rating','jobs_reviews.review','jobs_reviews.created_at','users.first_name','users.last_name','users.image')
                    ->get();
        if( Helpers::isWebview() ){
            
          return redirect(url('success'));  
        } 
                   
        return view('influencer.profile', compact('page_title', 'page_description','user', 'influencers', 'myprofile', 'categories','rating', 'platforms','authUser','WorkHistory'));
    }

    public function planSetting(Request $request){
        $user = auth()->user();

        if(!$user){
            $user = \Tymon\JWTAuth\Facades\JWTAuth::toUser(\Session::get('auth_token'));
        }

        if($user['type'] == User::TYPE_USER){
            $request->session()->flash('alert','You are not authorised to access this page.');
            return redirect()->back();
        }

        $categories = PlanCategories::where('status', PlanCategories::STATUS_ACTIVE)->with(['plansFront'=>function($q){
            $q->where('status', Plans::STATUS_ACTIVE);
        }])->get();


        $page_title = env('APP_NAME').' | '.$user['username'];
        $page_description = $user['username'].' plan setup';


        return view('influencer.plan_setting', compact('page_title', 'page_description','user', 'categories'));
    }

    public function storePlanSetting(Request $request){
        $inputs = $request->all();
        
        $user = auth()->user();
        $prices = $inputs['price'];
        foreach ($prices as $key => $price) {
            $plan = UserPlans::where(['user_id'=>$user['id'], 'plan_id'=>$key])->first();
            if(empty($plan)){
                $plan = new UserPlans();
                $plan->user_id = $user['id'];
                $plan->plan_id = $key;
            }
            $plan->price = floatval(number_format($price,2,".",""));
            $plan->save();
        }
        
        $user->account_verified=1;
        $user->save();
        $request->session()->flash('alert','Your plans are updated successfully. Enjoy promotions!');
        $url = '/my-profile';
        if($token = \Session::get('auth_token')){
            $url = $url . '?token='.$token ;
        }
        return redirect($url);
    }

    public function storeCustomPlanSetting(Request $request,$id){
        $setting = Helpers::get_settings();
        $inputs = $request->all();
        $plan = new Plans();
        $plan->user_id = $id;
        $plan->category_id = $inputs['category'];
        $plan->name = $inputs['plan_name'];
        $plan->description = $inputs['description'];
        $plan->status = 1;
        $plan->commission = $setting->commission; //Global
        $plan->save();
        $request->session()->flash('alert','Your plan is added successfully. Enjoy promotions!');
        $url = '/plan-setting';
        if($token = \Session::get('auth_token')){
            $url = $url . '?token='.$token ;
        }
        return redirect($url);
    }

    public function changePassword(Request $request){
        $inputs = $request->all();
        $user = auth()->user();  
        return view('pages/change_password');
    }

    public function saveChangePassword(Request $request){
        $inputs = $request->all();
        $user = auth()->user(); 
        if(Hash::check($request->current_password, $user->password)){       
            $chngePassword =  User::where('email', $user->email)->first();
            $chngePassword->password = bcrypt($request->new_password);
            $chngePassword->save();
            $message = 'Password updated successfully.';
            return redirect('/change-password')->with(['success' => $message]);
        }else{
            $message = 'Current password is wrong';
            return redirect('/change-password')->with(['error' => $message]);
        }
        return view('pages/change_password');
    }

    public function requestOTP(Request $request){
        $inputs = $request->all();

        $user = auth()->user();

        if(Hash::check($request->password, $user->password)){

            $country = Countries::where('id', $request->country_code)->first();
            $code = @$country['code']?'+'.$country['code']:'+91';
            $phone = $code.$request->phone;

            $six_digit = mt_rand(100000, 999999);

            $user->otp = $six_digit;

            $accountSid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
            $authToken  = config('app.twilio')['TWILIO_AUTH_TOKEN'];
            $client = new Client($accountSid, $authToken);
            try
            {
                $client->messages->create($phone,[
                        'from' => env('TWILIO_FROM'),
                        'body' => $six_digit.' is secret OTP for Enable Two-Factor Authentication for your Infuee account'
                    ]
                );
                $user->country_code = $request->country_code ;
                $user->phone = $request->phone ;
                $user->save();

                return response()->json(['success'=>true, 'message'=>'OTP sent to your registered mobile number']);

            }
            catch (\Twilio\Exceptions\RestException $e)
            {
                return response()->json(['message'=> 'Please enter correct phone number.']);
            }
            catch (Exception $e)
            {
                return response()->json(['message'=>'Please enter correct phone number.']);
            }
            
        }
        return response()->json(['message'=> 'Please enter correct password.']);
    }

    public function confirmOTP(Request $request){
        $inputs = $request->all();

        $user = auth()->user();

        if($user['otp'] == $inputs['otp']){

            $user->is_two_fa = 1;
            $user->save();
            return response()->json(['success'=>true, 'message'=>'Two-Factor Authentication enabled for your account.']);
        }

        return response()->json(['message'=> 'Please enter correct OTP.']);

    }

    public function disable2fa(Request $request){
        $user = auth()->user();

        $user->is_two_fa = 0;
        $user->save();
        return response()->json(['success'=>true, 'message'=>'Two-Factor Authentication disabled for your account.']);
    }
    
    public function bankSettings(){
        $user = auth()->user();
        $page_title = env('APP_NAME').' | '.$user['username']. ' bank settings';
        $page_description = $user['username']. ' bank settings';
        $bankSetting = BankSetting::where('user_id', $user['id'])->first();
        $external_accounts = [];
        $bank = false ;
        if(!empty($bankSetting) && $bankSetting->account_id){
            $settings = Setting::first();
            $stripe = new Stripe();
            $stripe = Stripe::make($settings['stripe_sk']);
            $bank = $stripe->account()->find($bankSetting['account_id']);
            $external_accounts = @$bank['external_accounts']['data'][0] ;
        }
        return view('influencer.bank_setting', compact('page_title', 'page_description','user', 'bankSetting', 'bank', 'external_accounts'));

    }

    protected function BankValidate(array $inputs)
    {
        return  $validator = Validator::make(
            $inputs,
            [
                'account_holder' => 'required',
                'account_number' => 'required',
                'sortCode' => 'required',
                'personal_id_number' => 'required',
                'documentFront' => 'required|mimes:jpeg,png,jpg|max:5000',
                'documentBack' => 'required|mimes:jpeg,png,jpg|max:5000',
                'additionalDocumentFront' => 'nullable|required_with:additionalDocumentBack|mimes:jpeg,png,jpg|max:5000',
                'additionalDocumentBack' => 'nullable|required_with:additionalDocumentFront|mimes:jpeg,png,jpg|max:5000',
            ],
            [
                'documentFront.max' => ' The PHOTO ID front may not be greater than 5 mb',
                'documentBack.max' => ' The PHOTO ID back may not be greater than 5 mb',
                'additionalDocumentFront.max' => ' The additional document front may not be greater than 5 mb',
                'additionalDocumentBack.max' => ' The additional document back may not be greater than 5 mb',
            ]
        );
    }

    public function deletebankaccount(Request $request){
        $user = auth()->user();
        $bankSetting = BankSetting::where('user_id', $user['id'])->first();
        $settings = Setting::first();
        $stripe = new Stripe();
        $stripe = Stripe::make($settings['stripe_sk']);
        $bank = $stripe->account()->delete($bankSetting['account_id']);
        $bankSetting->account_id = null;
        $bankSetting->save();
        return response()->json(['success'=>true, 'message'=> "Account deleted successfully, Please add another bank account to accepts orders."]);
    }

    public function savebankdetails(Request $request)
    {

        $name1 = '';
        $name2 = '';
        $inputs = $request->all();
        

        $validator = $this->BankValidate($inputs);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } 
        $user = auth()->user();
        $dob_check = $user['date_of_bith'];
            
        $request->documentFront;

        if ( $dob_check) {

            $name =   preg_match('/\s/', $request->account_holder);
            #detect white-space
            if ($name == 1) {
                $name_ex = explode(' ', $request->account_holder);
                $name1 = $name_ex[0];
                $name2 = $name_ex[1];
            } else {
                $name1 = $user['first_name'];
                $name2 = $user['last_name'];
            }
            try {
                $dob = explode('-', $user['date_of_bith']);
                
                $settings = Setting::first();
                $country = Countries::where('id', $user['country_code'])->first();

                $stripe = new Stripe();
                $stripe = Stripe::make($settings['stripe_sk']);
                // if (!$user['stripe_connect_account']) {

                $token = $stripe->tokens()->create([
                    'bank_account' => [
                        'country'  => "IN",
                        'currency' => "inr",
                        'account_holder_name' => $request->account_holder,
                        'routing_number' => $request->sortCode,
                        'account_number' => $request->account_number,
                    ],
                ]);
                
                $purpose = 'identity_document';
                $documentFront = $stripe->files()->create($request->documentFront, $purpose);
                $documentBack = $stripe->files()->create($request->documentBack, $purpose);
                if ($request->documentBack) {
                    $additionalDocumentFront = $stripe->files()->create($request->documentBack, $purpose);
                }
                if ($request->documentBack) {
                    $additionalDocumentBack = $stripe->files()->create($request->documentBack, $purpose);
                }

                $account = $stripe->account()->create([
                    'country' => "IN",
                    'type' => 'custom',
                    'email' => $user['email'],
                    'legal_entity' => [
                        'first_name' => @$name1 ?: $user['first_name'],
                        'last_name' => @$name2 ?: $user['last_name'],
                        'personal_id_number' => $inputs['personal_id_number'],
                        'type' => 'individual',
                        'dob' => [
                            'day' => @$dob[2],
                            'month' => @$dob[1],
                            'year' => @$dob[0],
                        ],
                        "address" => [
                            "line1" => $user['address'],
                            "line2" => '',
                            "city" => $user['city'],
                            'country' => @$country['ISO_code'],
                            "postal_code" => @$country['code']?'+'.$country['code']:'+91',
                            // "state" => $user['state'],
                        ],
                        "verification" => [
                            "additional_document" => !empty(@$additionalDocumentFront) ? $additionalDocumentFront['id'] : null,
                            "additional_document_back" => !empty(@$additionalDocumentBack) ? $additionalDocumentBack['id'] : null,
                            "document" => $documentFront['id'],
                            "document_back" =>  $documentBack['id'],
                        ]
                    ],
                    'capabilities' => [
                        'card_payments' => [
                            'requested' => 'true'
                        ],
                        'transfers' => [
                            'requested' => 'true'
                        ],
                    ],
                    'tos_acceptance' => ['date' => strtotime(date('Y-m-d h:i:s a')), 'ip' => $_SERVER['REMOTE_ADDR']],
                    'external_account' => $token['id'],
                ]);
                 
                $userBankSetting = BankSetting::where('user_id', $user['id'])->first();
                if(empty($userBankSetting)){
                    $userBankSetting = new BankSetting();
                    $userBankSetting->user_id = $user['id'];
                }
                $userBankSetting->bank_id = $token['id'];
                $userBankSetting->account_id = $account['id'];
                $userBankSetting->save();

                return redirect()->back()->with(['success' => "Bank Account details saved successfully."]);

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
            }
            SendNotification::where(['from' => Auth::guard('user')->user()->_id, 'to' => Auth::guard('user')->user()->_id, 'type' => 'account'])->update([
                'seen' => 1,
            ]);
            return redirect()->back()->with(['success' => 'Bank account added successfully', 'tabOpen' => 'test3']);
        } 
        return redirect()->back()->with(['error' => 'Please Provide required information like Date of birth , address', 'tabOpen' => 'test1']);
    }


    public function instacallback(Request $request){
        if(isset($_GET['code'])){
            $code = $_GET['code'];
            $access = User::getAccessToken($code);
            // echo '<pre>';print_r($access);die;
            if(isset($access['access_token'])){
                $access_token = $access['access_token'];
            }else{
                $request->session()->flash('alert',$access['error_message']);
            }
            echo $access_token; die;
            if(!empty($access_token) && isset($access_token)){
                $user = User::getUserProfileInfo($access_token);
            }
            var_dump($user);
            die;
        }
        return $request->all();
    }

    public function addRatings(Request $request){
        $inputs = $request->all();
        $item = OrderItems::where('id', $inputs['order_id'])->with('userPlan')->first();
        $influencer = $item->userPlan->user_id;
        $Rating = Rating::where('user_id',Auth::user()->id)->where('order_id',$inputs['order_id'])->where('influencer_id',$influencer)->first();
        if(empty($Rating)){
            $Rating = new Rating();   
        }
        $Rating->order_id = $inputs['order_id'];
        $Rating->review = $inputs['review'];
        $Rating->rating = $inputs['rating'];
        $Rating->influencer_id = $influencer;
        $Rating->user_id = Auth::user()->id;
        $Rating->save();
        // return redirect()->back()->with(['success' => 'Ratings added successfully']);
    }

    public function markDone(Request $request){
        $inputs = $request->all();
        $item = OrderItems::where('id', $inputs['order_id'])->first();
        $item->mark_done = $inputs['mark_done'];
        $item->save();
    }

    public function reviews($id){
        $ratings = Rating::where('influencer_id',$id)->with('user')->with('orderItem','orderItem.userPlan','orderItem.userPlan.allPlan','orderItem.userPlan.allPlan.allCategory')->get();
        // echo '<pre>';print_r($ratings); echo '</pre>';die;
        $user = auth()->user();
        $page_title = env('APP_NAME').' | '.$user['username']. ' reviews';
        $page_description = $user['username']. ' reviews';
        return view('influencer.reviews', compact('page_title', 'page_description','ratings'));
    }

    public function wallet(){
        $user = auth()->user() ;
        $wallet = \App\Models\UserWallet::where('user_id', $user['id'])->first();
        $transactions = \App\Models\UserWalletTransaction::where('wallet_id', @$wallet['id'] )->with(['user'])->latest()->paginate(20);
        $page_title = env('APP_NAME').' | '.$user['username']. ' wallet';
        $page_description = $user['username']. ' wallet and transactions';
        return view('influencer.wallet', compact('page_title', 'page_description','wallet', 'transactions'));   
    }

    public function fundWallet(){
        $user = auth()->user() ;
        $wallet = \App\Models\UserWallet::where('user_id', $user['id'])->first();
        $page_title = env('APP_NAME').' | '.$user['username']. ' fund wallet';
        $page_description = $user['username']. ' fund wallet';

        $bankDetails = BankSetting::where('user_id', $user['id'])->first();
        $cards=[];
        $fingerPrints = [];
        if(!empty($bankDetails)){
            $settings = Setting::first();
            $stripe = Stripe::make($settings['stripe_sk']);
            try{
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
            catch(\Exception $e){}
        }
        $month=Carbon::now()->month;
        $year=Carbon::now()->year;



        return view('influencer.fund_wallet', compact('page_title', 'page_description','wallet', 'cards','month','year'));   
    }

    

    public function loadWallet(Request $request){

        $inputs = $request->all();
        if($inputs['card_id']=='new'){
         $inputs['card_id']='';
        }
       
        $user = auth()->user() ;
        $wallet = \App\Models\UserWallet::where('user_id', $user['id'])->first();
         if(empty($wallet)){
            $wallet = \App\Models\UserWallet::updateOrCreate(['user_id' =>  $user['id'], 'amount' => 0]);
        }
        $amount = $inputs['amount'] ;
        $settings = Setting::first();
        $stripe = Stripe::make($settings['stripe_sk']);
  
        $bankDetails = BankSetting::where('user_id', $user['id'])->first();
        if(empty($bankDetails)){
            $bankDetails = new BankSetting();
            $bankDetails->user_id = $user['id'];
        }
    try{
            if(@$bankDetails['customer_id']){
            $stripe_customer_id = $bankDetails['customer_id'];
        }else{
            $customer = $stripe->customers()->create([
                'email' => $user->email,
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'metadata' => [
                    'dob' => @$user['date_of_birth']
                ],
            ]);
            $bankDetails->customer_id = $customer['id'];
            $stripe_customer_id = $customer['id'];
            $bankDetails->save();
        }
        if(empty($inputs['card_id'])){
        $token = $stripe->tokens()->create([
            'card' => [
                'name'    => $inputs['card_holder_name'],
                'number'    => $inputs['ccnum'],
                'exp_month' => $inputs['expiry_month'],
                'cvc'       => $inputs['cvc'],
                'exp_year'  => $inputs['expiry_year'],
            ],
        ]);
   
        // update card in user profile
        $card = $stripe->cards()->create($stripe_customer_id, $token['id']);
       }
        $charge = $stripe->paymentIntents()->create([
                'amount' => $amount,
                'currency' => 'INR',
                'customer' => $stripe_customer_id,
                'payment_method' => @$card['id']?:@$inputs['card_id'],
                'payment_method_types' => [
                    'card',
                ],
                'confirm' => true
        ]);
        $wallet->amount = $wallet->amount + $amount ;
        $wallet->save();


        $raw = [
            'wallet_id' => $wallet->id ,
            'amount' => $amount ,
            'transaction_type' => UserWalletTransaction::TYPE_CREDIT ,
            'description' => "You have loaded $". $amount." in your wallet",
            'transaction_id' => $charge['id'],
            'created_by' => $user['id']
        ];

        UserWalletTransaction::create($raw);

        return redirect('wallet');
        }catch (BadRequestException $e) {
                    return redirect()->back()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status' => 'error']);
                } catch (UnauthorizedException $e) {
                    return redirect()->back()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status' => 'error']);
                } catch (InvalidRequestException $e) {
                    return redirect()->back()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status' => 'error']);
                } catch (CardErrorException $e) {
                    return redirect()->back()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status' => 'error']);
                } catch (ServerErrorException $e) {
                    return redirect()->back()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status' => 'error']);
                } catch (NotFoundException $e) {
                    return redirect()->back()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status' => 'error']);
                } catch (ServerErrorException $e) {
                    return redirect()->back()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status' => 'error']);
                } catch (MissingParameterException $e) {
                    return redirect()->back()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status' => 'error']);
                }

            }

    public function invoicepdf(Request $request)
    {
       $transactions_id=$request->id;
       $wallet= \App\Models\UserWalletTransaction::where('transaction_id',$request->id)->first();
        $pdf = PDF::loadView('influencer.invoice', compact('wallet'));
        return $pdf->download('invoice.pdf');
    }

    public function withrawalWalletAmount( Request $request ){
        $user = auth()->user() ;
        $wallet = \App\Models\UserWallet::where('user_id', $user['id'])->first();
        $userAccount = BankSetting::where('user_id', $user['id'])->count();

        return view('influencer.withrawal-wallet-amount', compact('wallet','userAccount')); 
    }

    public function withrawalAmountRequest( Request $request ){
        $data = $request->all();
       // dd($data);
        $settings = Setting::first();
        $stripe = Stripe::make($settings['stripe_sk']);

        /*
        $requestSend = $stripe->transfers()->create([
            'amount' => 1.00,
            'currency' => 'usd',
            'destination' => 'acct_1HlXu02cUnWdhatb',
            'transfer_group' => 'ORDER_95',
            'description' => 'ORDER_95',
            'source_type' => 'card',
        
        ]);*/
        $transfer = $stripe->transfers()->create([
                /*'amount'    => 4,
                'currency'  => 'IN',
                'destination' => 'acct_1JWfWPSCaPk3GAJ8',
                'metadata' => [
                    'item' => "5"
                ],*/
                'amount' => 4,
                'currency' => "inr",
                'destination' => 'acct_1JWfWPSCaPk3GAJ8',
                'transfer_group' => '5'
                
            ]);
        
        //$requestSend =$stripe->transfers->all(['limit' => 3]);
        
        
        dd($transfer);
        
    }


}
