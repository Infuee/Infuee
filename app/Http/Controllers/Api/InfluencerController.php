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
use App\Models\UserPlatformStats;
use App\Models\SocialPlatform;
use Illuminate\Http\Request;
use Mail;
use JWTAuth;
use DB;
use Stripe;
use Tymon\JWTAuth\Exceptions\JWTException;

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


class InfluencerController extends Controller
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
     ** path="/api/influencers",
     *   tags={"User"},
     *   summary="Influencer List",
     *   operationId="influencers",
     *
     *   @OA\Parameter(
     *      name="page",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="search",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
    *      name="price_min",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *   @OA\Parameter(
    *      name="price_max",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="race",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *          type="array",
    *          @OA\Items(
    *              type="array",
    *              @OA\Items()
    *          ),
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="categories",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *          type="array",
    *          @OA\Items(
    *              type="array",
    *              @OA\Items()
    *          ),
    *      )
    *   ),
    *   @OA\Parameter(
    *      name="age",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="lat",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="lng",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="radious",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *   @OA\Parameter(
    *      name="raiting_filter",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="price_order",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="platforms",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *          type="array",
        *      @OA\Items(
        *          type="array",
        *          @OA\Items()
        *       ),
        *      )
    *   ),
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

    public function list(Request $request)
    {   
        $inputs = $request->all();
        try{
            $page_title = 'Influencers';
            $page_description = 'Browse Influencers';
            $query = User::withPlans()->where('status', User::STATUS_ACTIVE)->where('account_verified', 1)->where('type', User::TYPE_INFLUENCER);
            
            try{
                $user = JWTAuth::parseToken()->authenticate();
                if($user = auth()->user()){
                    $query = $query->where('id', '!=', $user['id']);
                }
            }catch(\Exception $e){}

            $lowPrice = $request->get('price_min');
            $maxPrice = UserPlans::orderBy('price', 'DESC')->pluck('price')->first();
            $highPrice = $request->get('price_max')?:$maxPrice;
            
            $lowPricePer = (int) ((100 * $lowPrice ) / $maxPrice);
            $highPricePer = (int) ((100 * $highPrice ) / $maxPrice);
              
            if($search = $request->get('search')){
                $query->where(function($q) use ($search){
                    $q->where('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
                });
            }

            if($get_category = $request->get('categories')){
                $get_category = array_filter(explode(",", $get_category ));
                $categoriesIds = Categories::whereIn('slug', $get_category)->orWhereIn('id', $get_category)->pluck('id')->toArray();
                if(count($categoriesIds) > 0) {
                    $query = $query->whereIn('category', $categoriesIds);
                }
            }

            if($age = $request->get('age') && $request->get('age') > 0) {
                $age = $request->get('age') ;
                $date = date('Y-m-d', strtotime('-'. $age. ' year')) ;
                $query = $query->whereDate( 'date_of_bith', '<=', $date );
            }

            $order = $request->get('price_order')?:1;
            
            $query_ = UserPlans::select('user_id', \DB::raw("min(price) as price") )->groupBy('user_id');
            if($order == 1){
                $query_ = $query_->orderBy('price', 'asc');
            }else{
                $query_ = $query_->orderBy('price', 'desc');
            }
                
            if($lowPrice && $lowPrice != ''){
                $query_ = $query_->where('price', '>=', $lowPrice);
            }
            if($highPrice && $highPrice != ''){
                $query_ = $query_->where('price', '<=', $highPrice);
            }

            $userPlans = $query_->get();

            $user_ = [];
            foreach ($userPlans as $key => $userPlan) {
                $user_[] = $userPlan['user_id'];
            }

            $query = $query->whereIn('id', $user_)->orderByRaw("FIELD(id , ".implode(",",$user_).") ASC");
            
            if($get_race= $request->get('race')){
                $get_race = array_filter(explode(",", $get_race ));
                $query = $query->where(function($q) use ($get_race){
                    if(count($get_race)){
                        foreach($get_race as $race){
                            $q->orWhere('race_id', 'LIKE', "%\"{$race}\"%");
                        }
                    }
                });
            }

            $lat = $request->get('lat') ;
            $lng = $request->get('lng') ;
            $radius = $request->get('radious') ;
            if( $lat & $lng ){
                $select = ['*'];
                $select[] = \DB::raw("111.111 *
                        DEGREES(ACOS(LEAST(1.0, COS(RADIANS(lat))
                             * COS(RADIANS(".$lat."))
                             * COS(RADIANS(lng - ". $lng ."))
                             + SIN(RADIANS(lat))
                             * SIN(RADIANS(". $lat ."))))) AS distance_in_km") ;
                $query = $query->select($select);

                $query = $query->having('distance_in_km', '<=', $radius );
            }

            if($ratings = $request->get('raiting_filter')){
                $usersRatings = \App\Models\JobReviews::select('rated_from')->where('rating', $ratings)->get();
                $usersStars = [];   
                foreach($usersRatings as $key => $usersRating){

                    $usersStars[]  = $usersRating['rated_from'];
                }
                $query = $query->whereIn('id', $usersStars);
            }

            if( $platforms_ = $request->get('platforms') ){
                $platforms_ = array_filter(explode(",", $platforms_ ));
                if(count($platforms_)){
                    $pla = SocialPlatform::whereIn('slug', $platforms_)->orWhereIn('id', $platforms_)->pluck('id')->toArray();

                    $userStats  = UserPlatformStats::whereIn('platform_id', $pla)->pluck('user_id')->toArray();
                    $query = $query->whereIn('id', $userStats);
                }

            }
            
            $influencers = $query->paginate(12);
            $i = 0;
            foreach($influencers AS $key => $influencer){
                $price =  $this->getPrice($influencer['id']);
                $influencers[$key]['price'] = '$'.$price;
                if(is_file(public_path("/media/users").'/'.@$influencer['image'])){
                    $influencers[$key]['image'] = asset('media/users/'.$influencer['image']) ;
                }else{
                    $influencers[$key]['image'] = asset('media/users/blank.png') ;
                }
                $influencers[$key]['social_links'] = $influencer->getSocialPlatformsHTML('app');
                $influencers[$key]['category'] = @$influencer->getCategory() ;
                $token = "" ;
                try{
                    $token = \Tymon\JWTAuth\Facades\JWTAuth::getToken() ;
                    $token = '?token='.$token ;
                }catch(\Exception $e){}

                $influencers[$key]['url'] = url('influencer'). '/' .( $influencer['username']?$influencer['username']:\Helpers::encrypt($influencer['id'])). '/profile'.$token ;

                $i++;
            }
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['image_link'] = asset('media/users/');
            $response['list'] = $influencers;
            $response['max_price'] = $maxPrice;
            $response['request'] = $request->all();
            
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
        // return $influencers->lastItem();
    }

    public function getPrice($user_id){
        $UserPlans = UserPlans::select('price','id')->where(['user_id'=>$user_id])->where('price','>',0)->orderBy('price', 'asc')->first();
        return @$UserPlans['price'];
    }


    /**
     * @OA\Post(
     ** path="/api/savebankdetails",
     *   tags={"User"},
     *   summary="Save Bank Details",
     *   operationId="savebankdetails",
     *
     *   @OA\Parameter(
     *      name="account_holder",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="account_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
        @OA\Parameter(
     *      name="ifscCode",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
        @OA\Parameter(
     *      name="personal_id_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ), 
        @OA\Parameter(
     *      name="documentFront",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="file"
     *      )
     *   ),
        @OA\Parameter(
     *      name="documentBack",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="file"
     *      )
     *   ),
        @OA\Parameter(
     *      name="additionalDocumentFront",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="file"
     *      )
     *   ),
        @OA\Parameter(
     *      name="additionalDocumentBack",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="file"
     *      )
     *   ),
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


    public function savebankdetails(Request $request)
    {
        try {
            $name1 = '';
            $name2 = '';
            $inputs = $request->all();
            $validator = $this->BankValidate($inputs);
            if ($validator->fails()) {
                $response['code'] = 404;
                $response['status'] = 'Fail';
                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }
            $user = JWTAuth::parseToken()->authenticate();
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
                            'currency' => "INR",
                            'account_holder_name' => $request->account_holder,
                            'routing_number' => $request->ifscCode,
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

                    $response['code'] = 200;
                    $response['status'] = "Success";
                    $response['message'] = "Bank Account details saved successfully.";
                    return response()->json($response);
                } catch (\Cartalyst\Stripe\Exception\BadRequestException $ex) {
                    return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);                
                } catch (\Cartalyst\Stripe\Exception\UnauthorizedException $ex) {
                    return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
                } catch (\Cartalyst\Stripe\Exception\InvalidRequestException $ex) {
                    return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
                } catch (\Cartalyst\Stripe\Exception\CardErrorException $ex) {
                    return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
                } catch (\Cartalyst\Stripe\Exception\ServerErrorException $ex) {
                    return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
                } catch (\Cartalyst\Stripe\Exception\NotFoundException $ex) {
                    return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
                } catch (\Cartalyst\Stripe\Exception\ServerErrorException $ex) {
                    return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
                } catch (\Cartalyst\Stripe\Exception\MissingParameterException $ex) {
                    return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
                }
                SendNotification::where(['from' => Auth::guard('user')->user()->_id, 'to' => Auth::guard('user')->user()->_id, 'type' => 'account'])->update([
                    'seen' => 1,
                ]);

                $response['code'] = 200;
                $response['status'] = "Success";
                $response['message'] = "Bank account added successfully";
                return response()->json($response);
            }
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        } 
    }

    /**
     * @OA\Get(
     ** path="/api/getbankdetails",
     *   tags={"User"},
     *   summary="Get Bank Details",
     *   operationId="getbankdetails",
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
    public function getbankdetails(){

        $user = JWTAuth::parseToken()->authenticate();
        $userBankSetting = BankSetting::where('user_id', $user['id'])->first();
        
        $failRes['code'] = 404;
        $failRes['status'] = "Fail";
        $failRes['message'] = "Bank details are not saved yet";

        if(empty($userBankSetting)){
            return response()->json($failRes);
        }

        try{
            $settings = Setting::first();
            $stripe = new Stripe();
            $stripe = Stripe::make($settings['stripe_sk']);

            if(empty($userBankSetting['account_id'])){
                return response()->json($failRes);
            }

            $account = $stripe->account()->find($userBankSetting['account_id']);
            $response['code'] = 200;
            $response['status'] = "success";
            $response['message'] = "Bank details";
            $response['bank'] = $account;
            return response()->json($response);
        
        } catch (\Cartalyst\Stripe\Exception\BadRequestException $ex) {
            return response()->json($failRes);
        } catch (\Cartalyst\Stripe\Exception\UnauthorizedException $ex) {
            return response()->json($failRes);
        } catch (\Cartalyst\Stripe\Exception\InvalidRequestException $ex) {
            return response()->json($failRes);
        } catch (\Cartalyst\Stripe\Exception\CardErrorException $ex) {
            return response()->json($failRes);
        } catch (\Cartalyst\Stripe\Exception\ServerErrorException $ex) {
            return response()->json($failRes);
        } catch (\Cartalyst\Stripe\Exception\NotFoundException $ex) {
            return response()->json($failRes);
        } catch (\Cartalyst\Stripe\Exception\ServerErrorException $ex) {
            return response()->json($failRes);
        } catch (\Cartalyst\Stripe\Exception\MissingParameterException $ex) {
            return response()->json($failRes);
        } catch (\Cartalyst\Stripe\Exception\StripeException $ex) {
            return response()->json($failRes);
        }
        
    }

    /**
     * @OA\Get(
     ** path="/api/deletebankdetails",
     *   tags={"User"},
     *   summary="Delete Bank Details",
     *   operationId="deletebankdetails",
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

    public function deletebankaccount(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $bankSetting = BankSetting::where('user_id', $user['id'])->first();
        $settings = Setting::first();
        $stripe = new Stripe();
        $stripe = Stripe::make($settings['stripe_sk']);
        $bank = $stripe->account()->delete($bankSetting['account_id']);
        $bankSetting->account_id = null;
        $bankSetting->save();
        return response()->json(['success'=>true, 'message'=> "Account deleted successfully, Please add another bank account to accepts orders."]);
    }

    protected function BankValidate(array $inputs)
    {
        return  $validator = Validator::make(
            $inputs,
            [
                'account_holder' => 'required',
                'account_number' => 'required',
                'ifscCode' => 'required',
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
    

    /**
     * @OA\Get(
     ** path="/api/change-password",
     *   tags={"User"},
     *   summary="Change Password",
     *   operationId="change-password",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *      name="current_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="new_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
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

    public function saveChangePassword(Request $request){
        try{
            $inputs = $request->all();
            $validator = Validator::make($inputs, [
                'current_password' => 'required',
                'new_password' => 'required',
            ]);
            if ($validator->fails()) {
                $response['code'] = 404;
                $response['status'] = "Fail";
                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }
            $user = JWTAuth::parseToken()->authenticate();
            if(Hash::check($request->current_password, $user->password)){       
                $chngePassword =  User::where('email', $user->email)->first();
                $chngePassword->password = bcrypt($request->new_password);
                $chngePassword->save();

                $response['code'] = 200;
                $response['status'] = "Success";
                $response['message'] = "Password updated successfully.";
                return response()->json($response);
            }else{
                $response['code'] = 404;
                $response['status'] = "Fail";
                $response['message'] = "Current password is wrong.";
                return response()->json($response);
            }
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        } 
    }

    /**
     * @OA\Get(
     ** path="/api/my-reviews",
     *   tags={"User"},
     *   summary="Influencer's Reviews List",
     *   operationId="my-reviews",
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
    public function myReviews(){

        $user = JWTAuth::parseToken()->authenticate();

        $ratings = \App\Models\Rating::where('influencer_id',$user['id'])->with('user')->with('orderItem','orderItem.userPlan','orderItem.userPlan.allPlan','orderItem.userPlan.allPlan.allCategory')->get();

        $data = [];
        foreach ($ratings as $key => $rating) {
            $data[$key]['user_name'] = $rating['user']['first_name'] . ' ' . $rating['user']['last_name'];
            $data[$key]['user_image'] = asset('media/users/'.$rating['user']['image']);
            $data[$key]['rating'] = $rating['rating'];
            $data[$key]['review'] = $rating['review'];
            $plan = @$rating['orderItem']['userPlan']['allPlan'] ;
            $data[$key]['plan_title'] = @$plan['allCategory']['name'] . ' ('. @$plan['name'] .')';
        }

        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = "Review list";
        $response['data'] = $data;
        
        return response()->json($response);
    }


    /**
     * @OA\Post(
     ** path="/api/delete-card",
     *   tags={"User"},
     *   summary="Delete Card",
     *   operationId="delete-card",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *      name="card_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
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
    public function deleteCard(){

        $user = JWTAuth::parseToken()->authenticate();

        $settings = Setting::first();
        $bankDetails = BankSetting::where('user_id', $user['id'])->first();
        $stripe = Stripe::make($settings['stripe_sk']);
        $card = $stripe->cards()->delete($bankDetails['customer_id'], $request->get('card_id'));
        if($card['deleted']){
            return response()->json(['code'=> 200,'success' => true,'message'=>"Card removed successfully"], 200);
        }
        return response()->json(['code'=> 403, 'success' => false, 'message'=>"Card not available"], 403);
    }

}
