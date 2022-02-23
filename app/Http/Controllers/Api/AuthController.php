<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Countries;
use Illuminate\Http\Request;
use Mail;
use JWTAuth;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;

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


class AuthController extends Controller
{
    /** @OA\Info(title="My First API", version="3") */

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
     * @OA\Post(
     ** path="/api/register",
     *   tags={"User"},
     *   summary="Register",
     *   operationId="register",
     *
     *   @OA\Parameter(
     *      name="name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="confirm_email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
        @OA\Parameter(
     *      name="confirm_password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="country_code",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="phone",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="dob",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="race",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="school",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),@OA\Parameter(
     *      name="address",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
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

     /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */

    protected function register(Request $request)
    {  
        try {  
            $inputs = $request->all();
            $validator = Validator::make($inputs, [
                'name' => 'required|string',
                // 'last_name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' =>'required',
                'dob' =>'required',
                'phone' =>'required',
                // 'address' =>'required',
            ]);
            if ($validator->fails()) {
                $response['code'] = 404;
                $response['status'] = "failed" ;
                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }

            $dateOfb = date('Y-m-d', strtotime($inputs['dob']) );

            $country = Countries::where('code', $inputs['country_code'])->first();

            $raw = [
                'first_name' => $inputs['name'],
                // 'last_name' => $inputs['last_name'],
                'email' => $inputs['email'], 
                'password' => Hash::make($request->get('password')), 
                'phone' => @$inputs['phone'], 
                'followers' => 0, 
                'status' => User::STATUS_ACTIVE, 
                'type' => User::TYPE_USER, 
                'image' => '', 
                'country' => @$country['id']?:83, 
                'country_code' => @$country['id']?:83, 
                // 'city' => $inputs['city'], 
                // 'state' => $inputs['state'], 
                'school' => @$inputs['school'],
                'race_id' => @$inputs['race'],
                'date_of_bith' => $dateOfb,
                'address' => @$inputs['address']
            ];
            if($user = User::create($raw)){
                $email = $user['email'];
                
                try{
                    Mail::send('email.signup', $confirmed = array('user_info'=>$user), function($message ) use ($email){
                        $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                        ->subject('Infuee ! Welcome')
                        ->to($email);
                    });
                }catch (\Exception $ex) {}

                $response['code'] = 200;
                $response['status'] = "Success";
                $response['message'] = "Account registered successfully.";
                return response()->json($response);
            }
        }catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()]);
        }
    }


    /**
     * @OA\Post(
     ** path="/api/login",
     *   tags={"User"},
     *   summary="Login",
     *   operationId="login",
     *
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="is_social",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *          type="integer"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="social_id",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="type",
     *      in="query",
     *      required=false,
     *      @OA\Schema(
     *          type="string"
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
    public function login(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|string',
            'password' => 'required_if:is_social,0|string',
            'is_social' => 'required|in:0,1',
            'type' => 'required_if:is_social,1|string',
            'social_id' => 'required_if:is_social,1|string',
        ],[
            'password.required_if' => "Password is required for manual login",
            'type.required_if' => "Please enter which social media type",
            'social_id.required_if' => "Please enter social id",
        ]);
        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = "failed" ;
            $response['message'] = $validator->errors()->first();
            return response()->json($response);
        }
        $email = $input['email'];
        $password = $input['password'];
        $user = User::where('email',$email)->first();
        if(!empty($user)){
            $user = User::where('email', $input['email'])->withTrashed()->first();
            if($user['deleted_at'] && $user['deleted_at'] != '' ){
                $response['code'] = 404;
                $response['status'] = 'Pending';
                $response['message'] = "Your account is deleted. Please contact to administrator.";
                return response()->json($response);
            }elseif($user->status == User::STATUS_PENDING){
                $response['code'] = 404;
                $response['status'] = 'Pending';
                $response['message'] = "Your account is under approval. Please contact to administrator.";
                return response()->json($response);
            }else if($user->status == User::STATUS_DEACTIVATED){
                $response['code'] = 404;
                $response['status'] = 'Deactivated';
                $response['message'] = "Your account is deactivated. Please contact to administrator.";
                return response()->json($response);
            }else if($user->status == User::STATUS_BAN){
                $response['code'] = 404;
                $response['status'] = 'Banned';
                $response['message'] = "Your account is banned. Please contact to administrator.";
                return response()->json($response);
            }
        }else{
            $response['code'] = 404;
            $response['status'] = 'failed';
            $response['message'] = "You are not registered with us. Please signup first.";
            return response()->json($response);
        }

        if(@$input['is_social'] == 1){
            $token = JWTAuth::fromUser($user);
        }else{
            if(!empty($user) && $user['is_two_fa']){
                
                $country = Countries::where('id', $user->country_code)->first();
                $code = @$country['code']?'+'.$country['code']:'+91';
                $phone = $code.$user->phone;

                $six_digit = mt_rand(1000, 9999);

                $user->otp = $six_digit;

                if($user->save()) {
                    $response['code'] = 200;
                    $response['status'] = 'success';
                    $response['message'] = "OTP sent on your registered phone number";  
                    $response['otp'] = $six_digit;
                    $response['is_two_fa'] = $user['is_two_fa'];
                    return response()->json($response); 
                } else {
                    $response['code'] = 403;
                    $response['status'] = 'success';
                    $response['message'] = "OTP cannot be sent to registered phone number. Invalid phone number or country code assigned.";  
                    return response()->json($response); 
                }
                     

                // $accountSid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
                // $authToken  = config('app.twilio')['TWILIO_AUTH_TOKEN'];
                // $client = new Client($accountSid, $authToken);
                // try
                // {
                //     $client->messages->create($phone,[
                //             'from' => env('TWILIO_FROM'),
                //             'body' => $six_digit.' is secret OTP for login into your Infuee account'
                //         ]
                //     );
                //     $user->save();
                        
                //     $response['code'] = 200;
                //     $response['status'] = 'success';
                //     $response['message'] = "OTP sent on your registered phone number";  
                //     $response['otp'] = $six_digit;
                //     $response['is_two_fa'] = $user['is_two_fa'];
                //     return response()->json($response); 
                // }
                // catch (\Twilio\Exceptions\RestException $e)
                // {
                //     $response['code'] = 403;
                //     $response['status'] = 'success';
                //     $response['message'] = "OTP cannot be sent to registered phone number. Invalid phone number or country code assigned.";  
                //     return response()->json($response); 
                // }
                // catch (Exception $e)
                // {
                //     $response['code'] = 403;
                //     $response['status'] = 'success';
                //     $response['message'] = "OTP cannot be sent to registered phone number. Invalid phone number or country code assigned.";  
                //     return response()->json($response); 
                // }

            }

            $credentials = $request->only('email', 'password');
            $token = JWTAuth::attempt($credentials);
        }
        if(!empty($token)){
            $response['token'] = $token;
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['type'] = $user->isUser() ? 'user' : 'influencer' ;
            $response['message'] = "You are logged in successfully!";
            $response['is_two_fa'] = $user['is_two_fa'];
            $response['user'] = $user;
        }else{
            $response['code'] = 404;
            $response['status'] = 'failed';
            $response['message'] = @$input['is_social'] == 1 ? "This user is not registered with us. Please signup first." : "You have entered wrong email id or Password";            
        }
        return response()->json($response);
    }

    /**
     * @OA\Post(
     ** path="/api/loginOTP",
     *   tags={"User"},
     *   summary="Login with OTP",
     *   operationId="loginOTP",
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
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
    public function loginOTP(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|string',
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = "failed" ;
            $response['message'] = $validator->errors()->first();
            return response()->json($response);
        }

        $user = User::where('email', $input['email'])->withTrashed()->first();
        
        if($user['otp'] != $input['otp']){
            $error = "OTP does not match. Please enter correct OTP.";
            $response['code'] = 404;
            $response['status'] = 'failed';
            $response['message'] = "You have entered wrong email id or OTP";  
            return response()->json($response);
        }

        $token = JWTAuth::fromUser($user);

        if(!empty($token)){
            $response['token'] = $token;
            $response['code'] = 200;
            $response['status'] = "success";
            $response['type'] = $user->isUser() ? 'user' : 'influencer' ;
            $response['message'] = "You are logged in successfully!";
            $response['user'] = $user;
        }else{
            $response['code'] = 404;
            $response['status'] = 'failed';
            $response['message'] = "You have entered wrong email id or Password";            
        }
        return response()->json($response);

    }

    /**
     * @OA\Post(
     ** path="/api/createNewPassword",
     *   tags={"User"},
     *   summary="Create New Password",
     *   operationId="createNewPassword",
     *   security={{ "apiAuth": {} }},
     *   @OA\Parameter(
     *      name="password",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
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
    public function createNewPassword(Request $request){
        try{
            $inputs = $request->all();
            $validator = Validator::make($inputs, [
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                $response['code'] = 404;
                $response['status'] = "failed";
                $response['message'] = $validator->errors()->first();
                return response()->json($response);
            }
            $user = JWTAuth::parseToken()->authenticate();
            if($user){       
                $chngePassword =  User::where('email', $user->email)->first();
                $chngePassword->password = bcrypt($request->password);
                $chngePassword->save();

                $response['code'] = 200;
                $response['status'] = "Success";
                $response['message'] = "Password updated successfully.";
                return response()->json($response);
            }else{
                $response['code'] = 404;
                $response['status'] = "failed";
                $response['message'] = "Authenticate failed.";
                return response()->json($response);
            }
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }

    }

    /**
     * @OA\Post(
     ** path="/api/verifyOTP",
     *   tags={"User"},
     *   summary="Login with OTP",
     *   operationId="verifyOTP",
     *   @OA\Parameter(
     *      name="email",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
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
    public function verifyOTP(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|string',
            'otp' => 'required',
        ]);
        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = "failed" ;
            $response['message'] = $validator->errors()->first();
            return response()->json($response);
        }

        $user = User::where('email', $input['email'])->withTrashed()->first();
        
        if($user['otp'] != $input['otp']){
            $error = "OTP does not match. Please enter correct OTP.";
            $response['code'] = 404;
            $response['status'] = 'failed';
            $response['message'] = "You have entered wrong email id or OTP";  
            return response()->json($response);
        }

        $token = JWTAuth::fromUser($user);

        if(!empty($token)){
            $response['token'] = $token;
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['type'] = $user->isUser() ? 'user' : 'influencer' ;
            $response['message'] = "Success";
            $response['user'] = $user;
        }else{
            $response['code'] = 404;
            $response['status'] = 'failed';
            $response['message'] = "You have entered wrong email id or Password";            
        }
        return response()->json($response);

    }

    /**
     * @OA\Post(
     ** path="/api/resendOTP",
     *   tags={"User"},
     *   summary="Resend OTP",
     *   operationId="resendOTP",
     *   @OA\Parameter(
     *      name="email",
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
    public function resendOTP(Request $request){
        
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required|string',
        ]);
        if ($validator->fails()) {
            $response['code'] = 404;
            $response['status'] = "failed" ;
            $response['message'] = $validator->errors()->first();
            return response()->json($response);
        }

        $user = User::where('email',$input['email'])->first();
        // $country = Countries::where('id', $user->country_code)->first();
        // $code = @$country['code']?'+'.$country['code']:'+91';
        // $phone = $code.$user->phone;

        $six_digit = mt_rand(1000, 9999);

        $user->otp = $six_digit;

        if($user->save()) {
            $response['code'] = 200;
            $response['otp'] = $six_digit;
            $response['status'] = 'success';
            $response['message'] = "OTP sent on your Email";  
            return response()->json($response);   
        } else {
            $response['code'] = 400;
            $response['status'] = 'success';
            $response['message'] = "Something went wrong !";  
            return response()->json($response);   
        }

        // $accountSid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
        // $authToken  = config('app.twilio')['TWILIO_AUTH_TOKEN'];
        // $client = new Client($accountSid, $authToken);
        // try
        // {
        //     $client->messages->create($phone,[
        //             'from' => env('TWILIO_FROM'),
        //             'body' => $six_digit.' is secret OTP for login into your Infuee account'
        //         ]
        //     );
        //     $user->save();
                
        //     $response['code'] = 200;
        //     $response['status'] = 'success';
        //     $response['message'] = "OTP sent on your registered phone number";  
        //     return response()->json($response); 
        // }
        // catch (\Twilio\Exceptions\RestException $e)
        // {
        //     $response['code'] = 403;
        //     $response['status'] = 'success';
        //     $response['message'] = "OTP cannot be sent to registered phone number. Invalid phone number or country code assigned.";  
        //     return response()->json($response); 
        // }
        // catch (Exception $e)
        // {
        //     $response['code'] = 403;
        //     $response['status'] = 'success';
        //     $response['message'] = "OTP cannot be sent to registered phone number. Invalid phone number or country code assigned.";  
        //     return response()->json($response); 
        // }
    }   


     /**
     * @OA\Post(
     ** path="/api/forget-password",
     *   tags={"User"},
     *   summary="Forget password",
     *   operationId="forget-password",
     *
     *   @OA\Parameter(
     *      name="email",
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
    public function forgetPassword(Request $request){ 
        $user = User::where('email', $request->email)->first();
        
        if ( !$user ){
            $response['code'] = 404;
            $response['status'] = 'Fail';
            $response['message'] = "This email is not registered with us.";  
            return response()->json($response);
        }
        
        $email = $request->email;
        
        // $token = time().$this->generateRandomString(30);
        $six_digit = mt_rand(1000, 9999);
        $user->otp = $six_digit;
        // $reset_password = DB::table('user_password_reset')->where('user_id', $user['id'])->first();
     
        // if($reset_password){
        //     DB::table('user_password_reset')->where('user_id', $user['id'])->update([
        //         'token' => $token, //change 60 to any length you want
        //     ]);
        // } else {
        //     DB::table('user_password_reset')->insert([
        //         'user_id' => $user['id'],
        //         'token' => $token, //change 60 to any length you want
        //     ]);
        // }
        // $data = array('link'=>url('password/reset/'.$token));
        //send mail to reset password
        // Mail::send('email.forget_password', $confirmed = array('user_info'=>$data), function($message ) use ($email){
        //     $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
        //     ->subject('Forgot Password')
        //     ->to($email);
        // });
        // $response['link'] = url('password/reset/'.$token);
        
        if($user->save()) {
            $response['code'] = 200;
            $response['otp'] = $six_digit;
            $response['status'] = "Success";
            $response['message'] = "OTP has been sent to email.";
            return response()->json($response);  
        } else {
            $response['code'] = 400;
            $response['status'] = 'success';
            $response['message'] = "Something went wrong !";  
            return response()->json($response);   
        }

    }
    private function generateRandomString($length) {
        $characters = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strtoupper($randomString);
    }

         /**
     * @OA\Get(
     ** path="/api/logout",
     *   tags={"User"},
     *   summary="Logout",
     *   operationId="Logout",
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

    public function logOut(Request $request)
    {
        try{
            $user = JWTAuth::parseToken()->authenticate();
            JWTAuth::parseToken()->invalidate(JWTAuth::getToken());
            $response['code'] = 200;
            $response['status'] = 'Success';
            $response['message'] = "Logout successfully";
            return response()->json($response);
        }
        catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }
    }
}
