<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Countries;
use App\Models\Categories;
use App\Models\PlanCategories;
use App\Models\Plans;
use App\Models\UserPlans;
use App\Models\Orders;
use App\Models\Race;
use App\Models\OrderItems;
use App\Models\InfluencerRequests;
use Illuminate\Http\Request;
use Helpers;
use Mail;
use JWTAuth;
use DB;
use URL;
use Tymon\JWTAuth\Exceptions\JWTException;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use App\Models\UserPlatformStats;
use App\Models\SocialPlatform;
use App\Models\Notification ;

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


class PagesController extends Controller
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
     * @OA\SecurityScheme(
     *     type="apiKey",
     *     description="Login with email and password to get the authentication token",
     *     name="Authorization",
     *     in="header",
     *     scheme="bearer",
     *     bearerFormat="JWT",
     *     securityScheme="apiAuth",
     * )
     */

    /**
     * @OA\Get(
     ** path="/api/get-account-info",
     *   tags={"User"},
     *   summary="Influencer List",
     *   operationId="get-account-info",
     *    security={{ "apiAuth": {} }},
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

     /**
     * @OA\Post(
     ** path="/api/post-account-info",
     *   tags={"User"},
     *   summary="Update profile",
     *   operationId="post-account-info",
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
        @OA\Parameter(
     *      name="first_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="last_name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="date_of_bith",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
        @OA\Parameter(
     *      name="phone",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
        @OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="category",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
         @OA\Parameter(
     *      name="myfile",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="file"
     *      )
     *   ),
        @OA\Parameter(
     *      name="facebook",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="twitter",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="pinterest",
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


    /**
     * @OA\Get(
     ** path="/api/countries",
     *   tags={"User"},
     *   summary="Countries List",
     *   security={{ "apiAuth": {} }},
     *   operationId="countries",
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

     /**
     * @OA\Get(
     ** path="/api/categories",
     *   tags={"User"},
     *   summary="Categories List",
     *   operationId="categories",
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

    public function getAccountInfo(Request $request)
    {
        try {
            $user =  JWTAuth::parseToken()->authenticate();
            $user->social_links = json_decode($user->social_links);
            if(empty($user->image) || !isset($user->image)){
                $user->image = 'blank.png';
            }
            $user->image = asset('media/users/'.$user->image);
            if($user->country_code ==null){
              $country = '';
            }else{
              $country = Countries::where('id', 'LIKE', $user->country_code)->first();

            }
           
            $user['country_code'] = !empty($country) ? $country->code : $user->country_code;
            $user['country_code_id'] = !empty($country) ? $country->id : $user->country_code;
            $user['country_flag'] = !empty($country) ? asset('media/country_flag/'.$country->flag) : '';
            $user['category'] = $user->getCategory();
            if(!empty($user) && isset($user)){
                
            $userPlatformStats = UserPlatformStats::join('social_platform', 'user_platform_stats.platform_id', '=', 'social_platform.id')
        ->select('user_platform_stats.*', 'social_platform.name','social_platform.slug')->where('user_platform_stats.user_id', $user->id)->get()->toArray();
        $user['userPlatforms'] = $userPlatformStats; 

        $workHistory=DB::table('jobs_reviews')
                    ->join('users','users.id','=','jobs_reviews.rated_from')
                     ->where(['rated_by' => $user->id])
                    ->select('jobs_reviews.rated_from','jobs_reviews.rating','jobs_reviews.review','jobs_reviews.created_at','users.first_name','users.last_name','users.image')
                    ->get();
//asset('media/users/'.$user->image)
                    $user['workHistory'] = $workHistory;
                    $user['imageUrl'] = asset('media/users/');
            }

            if(empty($user) || !isset($user)){
                $response['code'] = 404;
                $response['status'] = 'Fail';
                $response['message'] = "Something went wrong.";       
                return response()->json($response);
            }
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['user'] = $user;
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
        // return $influencers->lastItem();
    }

    public function postAccountInfo(Request $request){
        try {
            $inputs = $request->all();
            $validator = Validator::make($inputs, [
                "first_name" => 'required',
                "last_name" => 'required',
                "date_of_bith" => 'required',
                "country_code" => 'required',
                "phone" => 'required',
                "address" => 'required',
                "category"=> 'required_if:type,==,2',
            ]);
        
            if ($validator->fails()) {
                $response['code'] = 404;
                $response['status'] = $validator->errors()->first();
                $response['message'] = "missing parameters";
                return response()->json($response);
            }
            $user = JWTAuth::parseToken()->authenticate();
            if ($request->myfile) {
                $image = $request->file('myfile');
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path("/media/users");
                // echo $destinationPath.' '.$imagename; die;
                $image->move($destinationPath , $imagename);
                $user->image = $imagename;
            }else if($request->get('profile_avatar_remove')){
                $user->image = null;
            }
            $country = Countries::where('code', $inputs['country_code'])->first();
            $user->first_name = @$inputs['first_name'];
            $user->last_name = @$inputs['last_name'];
            $user->date_of_bith = date('Y-m-d', strtotime(@$inputs['date_of_bith']));
            $user->country_code = @$country['id'] ;
            $user->phone = @$inputs['phone'];
            $user->city = @$inputs['city'];
            $user->state = @$inputs['state'];
            $user->country = @$inputs['country'];
            $user->address = @$inputs['address'];
            $user->school = @$inputs['school'];
            $user->category = @$inputs['category'];
            $user->description = @$inputs['bio'];
            $array['facebook'] = @$inputs['facebook'];
            $array['twitter'] = @$inputs['twitter'];
            $array['pinterest'] = @$inputs['pinterest'];
            $user->social_links = json_encode($array);
            $user->save();

            $response['code'] = 200;
            $response['status'] = "Success";
            $response['message'] = "Profile updated successfully!";
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     ** path="/api/orders",
     *   tags={"User"},
     *   summary="Orders List",
     *   operationId="orders",
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
    public function getOrders(Request $request){
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $orderResponse = [];

            if($user->isInfluencer()){
                $userPlans = UserPlans::where('user_id', $user['id'])->pluck('id')->toArray();
                $orderItems = OrderItems::whereIn('user_plan_id', $userPlans)->with(['userPlan', 'userPlan.plan', 'order', 'order.user'])->latest()->paginate(10);
                foreach ($orderItems as $key => $item) {
                    $orderItems[$key]['Save_files_and_caption'] =  URL::to('order-download-zip',@$item['order']['order_id']);
                    $orderItems[$key]['linkify_description']=strip_tags(@$item['order']['description']);
                    if(is_file(public_path("/media/users").'/'.@$item['order']['user']['image'])){
                        $orderItems[$key]['image'] = asset('media/users/'.@$item['order']['user']['image']);
                    }else{
                        $orderItems[$key]['image'] = asset('media/users/blank.png');
                    }
                }
            }else{
                $orders = Orders::where('user_id', $user['id'])->pluck('id')->toArray();
                $orderItems = OrderItems::whereIn('order_id', $orders)->with(['userPlan', 'userPlan.influencer', 'userPlan.plan'])->latest()->paginate(10);
                foreach ($orderItems as $key => $item) {
                    if(is_file(public_path("/media/users").'/'.@$item['userPlan']['influencer']['image'])){
                        $orderItems[$key]['image'] = asset('media/users/'.@$item['order']['user']['image']);
                    }else{
                        $orderItems[$key]['image'] = asset('media/users/blank.png');
                    }
                    if($item->status == 1 && $item->rated()) {
                        $rating = $item->rated() ;
                        $orderItems[$key]['rating'] = $rating->rating;
                        $orderItems[$key]['status'] = "Completed";
                    }elseif($item->status == 1) {
                        $orderItems[$key]['status'] = "Accepted";
                    }
                    elseif($item->status == 0){
                        $orderItems[$key]['status'] = "Pending";
                    }else{
                        $orderItems[$key]['status'] = "Rejected";
                    }
                }
            }

            $response['code'] = 200;
            $response['status'] = "Success";
            $response['message'] = "Orders List";
            $response['orders'] = $orderItems;
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     ** path="/api/accept-order",
     *   tags={"User"},
     *   summary="Orders accept-order",
     *   operationId="accept-order",
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
     *   ),
     *   @OA\Parameter(
     *      name="order_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *)
     **/

    public function acceptOrder(Request $request){
        return (new \App\Http\Controllers\OrderController())->accept($request, $request->get('order_id'));
    }

    /**
     * @OA\Get(
     ** path="/api/view-order",
     *   tags={"User"},
     *   summary="Orders view-order",
     *   operationId="view-order",
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
     *   ),
     *   @OA\Parameter(
     *      name="order_id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *)
     **/

    public function viewOrder(Request $request){
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $orderResponse = [];
            if($user->isInfluencer()){
                $userPlans = UserPlans::where('user_id', $user['id'])->pluck('id')->toArray();
                $orderItems = OrderItems::whereIn('user_plan_id', $userPlans)->where('order_id', $request->order_id)->with(['userPlan', 'userPlan.plan', 'order', 'order.user'])->latest()->paginate(10);
                foreach ($orderItems as $key => $item) {
                    if(is_file(public_path("/media/users").'/'.@$item['order']['user']['image'])){
                        $orderItems[$key]['image'] = asset('media/users/'.@$item['order']['user']['image']);
                    }else{
                        $orderItems[$key]['image'] = asset('media/users/blank.png');
                    }
                }
            }else{
                $orderItems = OrderItems::where('order_id', $request->order_id)->with(['userPlan', 'userPlan.influencer', 'userPlan.plan'])->latest()->paginate(10);
                foreach ($orderItems as $key => $item) {
                    if(is_file(public_path("/media/users").'/'.@$item['userPlan']['influencer']['image'])){
                        $orderItems[$key]['image'] = asset('media/users/'.@$item['order']['user']['image']);
                    }else{
                        $orderItems[$key]['image'] = asset('media/users/blank.png');
                    }
                    if($item->status == 1 && $item->rated()) {
                        $rating = $item->rated() ;
                        $orderItems[$key]['rating'] = $rating->rating;
                        $orderItems[$key]['status'] = "Completed";
                    }elseif($item->status == 1) {
                        $orderItems[$key]['status'] = "Accepted";
                    }
                    elseif($item->status == 0){
                        $orderItems[$key]['status'] = "Pending";
                    }else{
                        $orderItems[$key]['status'] = "Rejected";
                    }
                }
            }

            $response['code'] = 200;
            $response['status'] = "Success";
            $response['message'] = "Orders Details";
            $response['data'] = $orderItems;
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
    }


    /**
     * @OA\Get(
     ** path="/api/transactions",
     *   tags={"User"},
     *   summary="transactions List",
     *   operationId="transactions",
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
    public function transactions(Request $request){

        try {
            $tra = [];
            $user = JWTAuth::parseToken()->authenticate();
            if($user->isInfluencer()){
                $transactions = \App\Models\Transactions::where('user_id', $user['id'])->with(['order', 'order.user', 'item', 'item.userPlan.allPlan'])->latest()->paginate(20);
                foreach ($transactions as $key => $transaction) {
                    $userdata = $transaction->getUserInfluencer(true);
                    $transactions[$key]['image'] = $userdata['image'];
                    
                    
                //     $array = [];
                //     $array['order_id'] = $transaction['order']['order_id'];
                //     $array['image'] = $userdata['image'];
                //     $array['name'] = $userdata['name'];
                //     $array['plan'] = $transaction['item']['userPlan']['plan']['name'];
                //     $array['price'] = number_format($transaction['amount'],2);
                    $transactions[$key]['status'] = $transaction['status']==1 ? "Paid" : "Failed";
                //     $array['date'] = date('d / M / Y', strtotime($transaction['created_at']));
                //     $tra[] = $array;
                }

            }else{
                $transactions = \App\Models\Transactions::where('user_id', $user['id'])->with(['order', 'order.user', 'item', 'item.userPlan.allPlan'])->latest()->paginate(20);
                foreach ($transactions as $key => $transaction) {
                    $userdata = $transaction->getUserInfluencer(true);
                    $transactions[$key]['image'] = $userdata['image'];


                //     $array = [];
                //     $array['order_id'] = $transaction['order']['order_id'];
                //     $userdata = $transaction->getUserInfluencer();
                //     $array['image'] = $userdata['image'];
                //     $array['name'] = $userdata['name'];
                //     $array['plan'] = $transaction['item']['userPlan']['plan']['name'];
                //     $array['price'] = number_format($transaction['amount'],2);
                    $transactions[$key]['status'] = $transaction['status']==1 ? "Paid" : "Failed";
                //     $array['date'] = date('d / M / Y', strtotime($transaction['created_at']));
                //     $tra[] = $array;
                }
            }

            $response['code'] = 200;
            $response['status'] = "Success";
            $response['message'] = "Transactions List";
            $response['orders'] = $transactions;
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }

    }
    

    /**
     * @OA\Post(
     ** path="/api/updateProfileImage",
     *   tags={"User"},
     *   summary="Influencer List",
     *   operationId="updateProfileImage",
     *   security={{ "apiAuth": {} }},
     *   @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(

     *                 @OA\Property(
     *                     description="file to upload",
     *                     property="file",
     *                     type="file",
     *                     format="file",
     *                 ),
     *                 required={"file"}
     *             )
     *         )
     *     ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="profile_avatar_remove",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *           type="number"
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
    public function updateProfileImage(Request $request){
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            "file" => 'required'
        ]);
        
        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = $validator->errors()->first();
            $response['message'] = "missing parameters";
            return response()->json($response);
        }   
        $user = JWTAuth::parseToken()->authenticate();
        if ($request->file) {
            $image = $request->file('file');
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path("/media/users");
            // echo $destinationPath.' '.$imagename; die;
            $image->move($destinationPath , $imagename);
            $user->image = $imagename;
        }else if($request->get('profile_avatar_remove')){
            $user->image = null;
        }
        $user->save();
        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = $user->image ? "Profile image updates successfully" :"Profile image removed successfully" ;
        return response()->json($response);
    }


    public function countries(Request $request)
    {
        try {
            $countries = Countries::all();
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['countries'] = $countries;
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
    }
    public function categories(Request $request)
    {
        try {
            $categories = Categories::where('status', 1)->get()->toArray(); 
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['categories'] = $categories;
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
    }

         /**
     * @OA\Get(
     ** path="/api/social-platform",
     *   tags={"User"},
     *   summary="Social Platform List",
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

    public function socialPlatform(Request $request)
    {
        try {
            $socialPlatform = SocialPlatform::where('status', 1)->get()->toArray(); 
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['socialPlatform'] = $socialPlatform;
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
    }


    /**
     * @OA\Post(
     ** path="/api/become-influencer",
     *   tags={"User"},
     *   summary="Become Influencer",
     *   operationId="become-influencer",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
        @OA\Parameter(
     *      name="i_username",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="category",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
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

    public function becomeInfluencer( Request $request )
    {
        try {
            $categories = Categories::where('status', 1)->pluck('name', 'id')->toArray();

            $inputs = $request->all();

            $validator = Validator::make($inputs, [
                'i_username' => 'required',
                'category' => 'required',
            ]);
        
            if ($validator->fails()) {
                $response['code'] = 404;
                $response['status'] = "Fail";
                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }
            $user = JWTAuth::parseToken()->authenticate();
            $instaDetails = User::getInstaDetails($inputs['i_username']);
            // echo '<pre>';print_r($instaDetails); die;
            $raw = [
                'user_id' => $user->id,
                'username' => @$inputs['i_username'],
                'followers' => @$instaDetails['followers'],
                'f_username' => @$inputs['f_username'],
                'f_followers' => @$inputs['f_followers'],
                'category' => @$inputs['category'],
                'account_verified' => 0,
                'status' => 0
            ];

            $user_ = User::find($user->id);

            if(InfluencerRequests::create($raw)){
                $user_->followers = @$instaDetails['followers'];
                $user_->save();
                $response['code'] = 200;
                $response['status'] = "Success";
                $response['message'] = "Request sent to admin.";
                return response()->json($response);
            }
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     ** path="/api/influencer/{username}/profile",
     *   tags={"User"},
     *   summary="Get Influencer's details",
     *   operationId="influencer/{username}/profile",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="username",
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
    public function influencerProfile($username = false, Request $request){
        if($username){
            $username = $request->username;
        }
        $user = User::where('email', $username)->with(['countryDetails'])->first();
        \Auth::guard('web')->login($user);
        $user->social_links = json_decode($user->social_links);
        if(empty($user->image) || !isset($user->image)){
            $user->image = 'blank.png';
        }
        $user->image = asset('media/users/'.$user->image);
        $user['country_flag'] = asset('media/country_flag/'.$user->getCountryFlag());
        $page_title = env('APP_NAME').' | '.$username;
        $page_description = 'Privacy Policy';
        
        if($user->type == 2){
            $rating = Helpers::get_ratings_average($user->id);
        }

        $query = User::withPlans()->where('email', '!=', $username)->where('status', User::STATUS_ACTIVE)->where('type', User::TYPE_INFLUENCER);
        
        // $influencers = $query->paginate(4);

        $categories = PlanCategories::where('status', PlanCategories::STATUS_ACTIVE)->with(['plans'=>function($q){
            $q->where('status', Plans::STATUS_ACTIVE);
        }])->get()->toArray();
        $UserPlans = UserPlans::select('plan_id')->where('user_id',$user->id)->get()->toArray();
        if(!empty($UserPlans)){
            foreach($UserPlans AS $UserPlan){
                $user_plans[] = $UserPlan['plan_id'];
            }
        }else{
            $user_plans = array();
        }
        $response['code'] = 200;
        $response['status'] = "Success";
        // $response['influencers'] = $influencers;
        $arr = array();
        foreach(@$categories as $key => $category){
            if(count($category['plans'])){
                if(isset($user_plans)){
                    $purchase = 'No';
                    $plansArr = array();
                    foreach($category['plans'] as $plan){
                        if(in_array($plan['id'],$user_plans)){
                            // echo 'price '.$this->getUserPrice(true,$user,$plan['id']);
                            if($this->getUserPrice(true,$user,$plan['id'])){
                                $userplan_id = UserPlans::select('id')->where('user_id',$user->id)->where('plan_id',$plan['id'])->first();
                                $plan['userplan_id'] = $userplan_id->id;
                                $plan['price'] = $this->getUserPrice(true,$user,$plan['id']);
                                $plansArr[] = $plan;
                                $purchase = 'Yes';
                            }
                        }
                    }
                }else{
                    $purchase = 'Yes';
                }
                if($purchase == 'Yes'){
                    $category['plans'] = $plansArr;
                    $arr[] = $category;
                }
            }
        }
        $userPlatformStats = UserPlatformStats::join('social_platform', 'user_platform_stats.platform_id', '=', 'social_platform.id')
        ->select('user_platform_stats.*', 'social_platform.name','social_platform.slug')->where('user_platform_stats.user_id', $user->id)->get()->toArray();

        
        $plans = [];
        $planCategories = PlanCategories::where('status', PlanCategories::STATUS_ACTIVE)->with(['plansFront'=>function($q) {
            $q->where('status', Plans::STATUS_ACTIVE)->orderBy('id','desc');
        }])->get();
        foreach ($planCategories as $key => $category) {
            foreach ($category['plansFront'] as $secondkey => $plan) {
                if($plan->user_id == $user->id) {
                    $planCategories[$key]['plansFront'][$secondkey]['price'] = $plan->getUserPrice(false, $user) ;
                } else {
                  unset($planCategories[$key]['plansFront'][$secondkey]);  
                }
            }
        }
        
        $response['user'] = $user;
        $response['categories'] = $arr;
        $response['planCategories'] = $planCategories?$planCategories:[];
        $response['userPlatforms'] = $userPlatformStats?$userPlatformStats:[];
        $response['rating'] = $rating;
        return response()->json($response);
        // return view('influencer.profile', compact('page_title', 'page_description','user', 'influencers','categories','user_plans','rating'));

    }

    public function getUserPrice($shouldIncludePriceSymbol = true,$user='',$plan_id=''){
        if(empty($user)){
            $user = JWTAuth::parseToken()->authenticate();
        }
        $userPlan = UserPlans::where(['user_id'=>$user['id'], 'plan_id'=>$plan_id])->first();
        if(!@$userPlan['price']){
            return "" ;
        }
        if($shouldIncludePriceSymbol){
            return '$ '.number_format(@$userPlan['price'],2);
        }
        return number_format(@$userPlan['price'],2);
    }
    
    /**
     * @OA\Post(
     ** path="/api/fa-send-otp",
     *   tags={"User"},
     *   summary="Send OTP to enable 2 factor authorisation ",
     *   operationId="fa-send-otp",
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="mobile_number",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
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
    public function sendOTP(Request $request){

        $inputs = $request->all();

        $user = JWTAuth::parseToken()->authenticate();

        if(Hash::check($request->password, $user->password)){

            $country = Countries::where('id', $request->country_code)->orWhere('code', $request->country_code)->first();
            $code = @$country['code']?'+'.$country->code:'+91';
            $phone = $code.$request->mobile_number;

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

    /**
     * @OA\Post(
     ** path="/api/enable-fa",
     *   tags={"User"},
     *   summary="Enable 2FA",
     *   operationId="enable-fa",
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="otp",
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
    public function confirmOTP(Request $request){
        $inputs = $request->all();

        $user = JWTAuth::parseToken()->authenticate();

        if($user['otp'] == $inputs['otp']){

            $user->is_two_fa = 1;
            $user->save();
            return response()->json(['code'=>200,'status'=>'success', 'message'=>'Two-Factor Authentication enabled for your account.']);
        }

        return response()->json(['message'=> 'Please enter correct OTP.']);

    }

    /**
     * @OA\Post(
     ** path="/api/disable-fa",
     *   tags={"User"},
     *   summary="Disable 2FA",
     *   operationId="disable-fa",
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
    public function disable2fa(Request $request){
        $user = JWTAuth::parseToken()->authenticate();

        $user->is_two_fa = 0;
        $user->save();
        return response()->json(['code'=>200,'status'=>'success', 'message'=>'Two-Factor Authentication disabled for your account.']);
    }

    /**
     * @OA\Post(
     ** path="/api/add-plan",
     *   tags={"User"},
     *   summary="Add Plan by influencer",
     *   operationId="add-plan",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *      name="category",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
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
     *      name="price",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
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
    public function addPlan(Request $request){
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            "category" => "required",
            "name" => "required",
            "description" => "required",
            "price" => "required"
        ]);
        
        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = $validator->errors()->first();
            $response['message'] = "missing parameters";
            return response()->json($response);
        }   
        $user = JWTAuth::parseToken()->authenticate();

        $setting = Helpers::get_settings();
        $inputs = $request->all();
        $plan = new Plans();
        $plan->user_id = $user['id'];
        $plan->category_id = $inputs['category'];
        $plan->name = $inputs['name'];
        $plan->description = $inputs['description'];
        $plan->status = 1;
        $plan->commission = $setting->commission; //Global
        $plan->save();
        
        $userplan = new UserPlans();
        $userplan->user_id = $user['id'];
        $userplan->plan_id = $plan->id;
        $userplan->price = floatval(number_format($inputs['price'],2,".",""));
        $userplan->save();

        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = "Plan saved successfully";
        return response()->json($response);
    }


    /**
     * @OA\Get(
     ** path="/api/myplans",
     *   tags={"User"},
     *   summary="Get Influencer's Plans",
     *   operationId="myplans",
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
    public function myplans(Request $request){
        $plans = [];
        $user = JWTAuth::parseToken()->authenticate();
        \Session::put('influencer', $user['id']);
        $categories = PlanCategories::where('status', PlanCategories::STATUS_ACTIVE)->with(['plansFront'=>function($q){
            $q->where('status', Plans::STATUS_ACTIVE);
        }])->get();
        foreach ($categories as $key => $category) {
            foreach ($category['plansFront'] as $secondkey => $plan) {
                $categories[$key]['plansFront'][$secondkey]['price'] = $plan->getUserPrice(false, $user) ;
            }
        }

        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = "Plan List";
        $response['plans'] = $categories;
        return response()->json($response);
    }

    /**
     * @OA\Post(
     ** path="/api/update-plan-setting",
     *   tags={"User"},
     *   summary="Update Plan prices of influencer.   This API cannot be called from swagger. Please inetegrate in your app send request format as discussed",
     *   operationId="update-plan-setting",
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

    public function updatePlanSetting(Request $request){

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            "plans" => "required"
        ]);
        
        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = $validator->errors()->first();
            $response['message'] = "missing parameters";
            return response()->json($response);
        }   
        $user = JWTAuth::parseToken()->authenticate();

        foreach ($inputs['plans'] as $key => $value) {
            $plan = UserPlans::where(['user_id'=>$user['id'], 'plan_id'=>$value['id']])->first();
            if(empty($plan)){
                $plan = new UserPlans();
                $plan->user_id = $user['id'];
                $plan->plan_id = $value['id'];
            }
            $plan->price = floatval(number_format($value['price'],2,".",""));
            $plan->save();
        }
        $response['code'] = 200;
        $response['status'] = $validator->errors()->first();
        $response['message'] = "Plans are saved successfully";
        return response()->json($response);
    }


    /**
     * @OA\Get(
     ** path="/api/races",
     *   tags={"User"},
     *   summary="Countries List",
     *   security={{ "apiAuth": {} }},
     *   operationId="races",
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

    public function races(Request $request)
    {
        try {
            $charactersLength = Race::all();
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['countries'] = $charactersLength;
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
    }


    /**
     * @OA\Get(
     ** path="/api/notifications",
     *   tags={"User"},
     *   summary="Notifications List",
     *   security={{ "apiAuth": {} }},
     *   operationId="notifications",
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
    public function notifications(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $getAllNotificatios = Notification::where('user_id',$user['id'])
         ->orderBy('id', 'DESC')
         ->paginate(20);   
        foreach($getAllNotificatios as $notificationId ){
            Notification::where('id', $notificationId->id)->where('user_id', auth()->user()->id)->update(['seen' => '1']);
        }

        $response['code'] = 200;
        $response['status'] = "Success";
        $response['data'] = $getAllNotificatios;
        return response()->json($response);
    }

    /**
     * @OA\Post(
     ** path="/api/notification/delete",
     *   tags={"User"},
     *   summary="delete Notifications",
     *   security={{ "apiAuth": {} }},
     *   operationId="notification-delete",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
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

    public function notificationDelete(Request $request){
        $notification = Notification::whereId($request->id)->first();
        if(empty($notification)){
            return response()->json(['status'=>false, 'message' => "This notification does not exists."]);
        }
        $notification->delete();
        return response()->json(['status'=>true, 'message' => "This notification is deleted successfully" ]);
    }


    /**
     * @OA\Get(
     ** path="/api/notification/clear",
     *   tags={"User"},
     *   summary="clear Notifications",
     *   security={{ "apiAuth": {} }},
     *   operationId="notification-clear",
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
    public function notificationClear(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $clearNotification = Notification::where(['user_id' => $user['id']])->get();
        if($clearNotification->count()<= 0){
            return response()->json(['status'=>false, 'message' => "Notifications does not exists."]);
        }
        foreach($clearNotification as $org) 
        {
            $org->delete();
        }
        return response()->json(['status'=>true, 'message' => " Notification  cleared  successfully" ]); 
    }


    /**
     * @OA\Get(
     ** path="/api/transaction",
     *   tags={"User"},
     *   summary="transaction",
     *   security={{ "apiAuth": {} }},
     *   operationId="transaction",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
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
    public function transaction(Request $request){
        try {
            $tra = [];
            $user = JWTAuth::parseToken()->authenticate();
            if($user->isInfluencer()){
                $transaction = \App\Models\Transactions::where('user_id', $user['id'])->where('id', $request->id)->with(['order', 'order.user', 'item', 'item.userPlan.allPlan'])->latest()->first();
                    $userdata = $transaction->getUserInfluencer(true);
                    $transaction['image'] = $userdata['image'];
                    
                    
                //     $array = [];
                //     $array['order_id'] = $transaction['order']['order_id'];
                //     $array['image'] = $userdata['image'];
                //     $array['name'] = $userdata['name'];
                //     $array['plan'] = $transaction['item']['userPlan']['plan']['name'];
                //     $array['price'] = number_format($transaction['amount'],2);
                    $transaction['status'] = $transaction['status']==1 ? "Paid" : "Failed";
                //     $array['date'] = date('d / M / Y', strtotime($transaction['created_at']));
                //     $tra[] = $array;
                // }

            }else{
                $transaction = \App\Models\Transactions::where('user_id', $user['id'])->where('id', $request->id)->with(['order', 'order.user', 'item', 'item.userPlan.allPlan'])->latest()->first();
                    $userdata = $transaction->getUserInfluencer(true);
                    $transaction['image'] = $userdata['image'];


                //     $array = [];
                //     $array['order_id'] = $transaction['order']['order_id'];
                //     $userdata = $transaction->getUserInfluencer();
                //     $array['image'] = $userdata['image'];
                //     $array['name'] = $userdata['name'];
                //     $array['plan'] = $transaction['item']['userPlan']['plan']['name'];
                //     $array['price'] = number_format($transaction['amount'],2);
                    $transaction['status'] = $transaction['status']==1 ? "Paid" : "Failed";
                //     $array['date'] = date('d / M / Y', strtotime($transaction['created_at']));
                //     $tra[] = $array;
                // }
            }

            $response['code'] = 200;
            $response['status'] = "Success";
            $response['message'] = "Transaction Details";
            $response['orders'] = $transaction;
            return response()->json($response);
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
    }

}
