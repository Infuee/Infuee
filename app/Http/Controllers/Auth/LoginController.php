<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\User;
use Session;
use App\Models\Countries;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email',
            'password' => 'required',
        ]);
    }

    public function login(Request $request)
    {
        $input = $request->all();

        
        if(@$input['resend']){

            $user = User::where('email', $input['email'])->first();

            if($user['deleted_at'] && $user['deleted_at'] != '' ){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is deleted. Please contact to administrator.']);
            }elseif($user['status'] == User::STATUS_PENDING){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is under approval. Please contact to administrator.']);
            }else if($user['status'] == User::STATUS_DEACTIVATED){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is deactivated. Please contact to administrator.']);
            }else if($user['status'] == User::STATUS_BAN){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is banned. Please contact to administrator.']);
            }
            

            $country = Countries::where('id', @$user['country_code'])->first();
            $code = @$country['code']?'+'.$country['code']:'+91';
            $phone = $code.$user->phone;

            $six_digit = mt_rand(100000, 999999);

            $user->otp = $six_digit;

            $accountSid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
            $authToken  = config('app.twilio')['TWILIO_AUTH_TOKEN'];
            $client = new Client($accountSid, $authToken);
            try
            {
                $client->messages->create($phone,[
                        'from' => env('TWILIO_FROM'),
                        'body' => $six_digit.' is secret OTP for login into your Infuee account'
                    ]
                );
                $user->save();
                $again = true;
                return view('auth.login', compact('user', 'again'));
            }
            catch (\Twilio\Exceptions\RestException $e)
            {
                return redirect()->back()->withInput()->with(['error' => 'Please enter correct phone number.']);
            }
            catch (Exception $e)
            {
                return redirect()->back()->withInput()->with(['error' => 'Please enter correct phone number.']);
            }
        }
    

        if(@$input['otp']){

            $user = User::where('email', $input['email'])->withTrashed()->first();
            if($user['deleted_at'] && $user['deleted_at'] != '' ){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is deleted. Please contact to administrator.']);
            }elseif($user['status'] == User::STATUS_PENDING){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is under approval. Please contact to administrator.']);
            }else if($user['status'] == User::STATUS_DEACTIVATED){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is deactivated. Please contact to administrator.']);
            }else if($user['status'] == User::STATUS_BAN){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is banned. Please contact to administrator.']);
            }

            if($user['otp'] != $input['otp']){
                $again = true;
                $error = "OTP does not match. Please enter correct OTP.";
                return view('auth.login', compact('user', 'again', 'error'));
            }

            $email = $input['email'];
            $otp = $input['otp'];
            $remember =  $request->has('remember') ? true : false;
            $attempt = Auth::guard('web')->login($user);
            if ($remember == 'true') {
                setcookie('password', $password, time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie('email', $email, time() + (86400 * 30), "/"); // 86400 = 1 day
            } else {
                setcookie('password', '', time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie('email', '', time() + (86400 * 30), "/"); // 86400 = 1 day
            }
            
            $request->session()->flash('alert', 'You are logged in successfully!');
            if(@$input['beInfluencer']){
                return redirect('be-influencer');
            }
            return redirect()->intended($this->redirectTo);
        }


        $validator = $this->validator($input);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } 
        $email = $input['email'];
        $password = $input['password'];
        $remember =  $request->has('remember') ? true : false;
        $attempt = Auth::guard('web')->attempt(['email' => $email, 'password' => $password], $remember);
        if ($remember == 'true') {
            setcookie('password', $password, time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie('email', $email, time() + (86400 * 30), "/"); // 86400 = 1 day
        } else {
            setcookie('password', '', time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie('email', '', time() + (86400 * 30), "/"); // 86400 = 1 day
        }
        if ($attempt) {
            $user = auth()->user();

            if($user['deleted_at'] && $user['deleted_at'] != '' ){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is deleted. Please contact to administrator.']);
            }elseif($user['status'] == User::STATUS_PENDING){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is under approval. Please contact to administrator.']);
            }else if($user['status'] == User::STATUS_DEACTIVATED){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is deactivated. Please contact to administrator.']);
            }else if($user['status'] == User::STATUS_BAN){
                Auth::logout();
                return redirect()->back()->withInput()->with(['error' => 'Your account is banned. Please contact to administrator.']);
            }

            if($user['is_two_fa']){
                Auth::logout();

                $country = Countries::where('id', $user->country_code)->first();
                $code = @$country['code']?'+'.$country['code']:'+91';
                $phone = $code.$user->phone;

                $six_digit = mt_rand(100000, 999999);

                $user->otp = $six_digit;

                $accountSid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
                $authToken  = config('app.twilio')['TWILIO_AUTH_TOKEN'];
                $client = new Client($accountSid, $authToken);
                try
                {
                    $client->messages->create($phone,[
                            'from' => env('TWILIO_FROM'),
                            'body' => $six_digit.' is secret OTP for login into your Infuee account'
                        ]
                    );
                    $user->save();

                    return view('auth.login', compact('user'));
                }
                catch (\Twilio\Exceptions\RestException $e)
                {
                    return redirect()->back()->withInput()->with(['error' => "OTP cannot be sent to registered phone number. Invalid phone number or country code assigned." ]);
                }
                catch (Exception $e)
                {
                    return redirect()->back()->withInput()->with(['error' => "OTP cannot be sent to registered phone number. Invalid phone number or country code assigned." ]);
                }

            }
          
            $request->session()->flash('alert', 'You are logged in successfully!');
            if(@$input['beInfluencer']){
                return redirect('be-influencer');
            }
            
            return redirect()->intended($this->redirectTo);
        }
        $message = 'You have entered wrong email id or Password';
        return redirect()->back()->withInput()->with(['error' => $message]);
    }
}
