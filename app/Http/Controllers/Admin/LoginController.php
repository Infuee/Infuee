<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Admin;
use Cookie;

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
    protected $redirectTo = 'admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|email',
            'password' => 'required|min:8',
        ]);
    }
    public function showLoginForm()
    {
        $user = Auth::guard('admin')->user();
        //print_r($user);exit;
        if (!$user) {
            if (isset($_COOKIE['password'])) {

                if (!empty($_COOKIE['password'])) {
                    $user['email'] = $_COOKIE['email'];
                    $user['password'] = $_COOKIE['password'];
                    return view('admin.auth.login', compact('user'));
                } else {
                    return view('admin.auth.login');
                }
            } else {
                return view('admin.auth.login');
            }
        } else {
            return redirect('/admin/home');
        }
    }

    public function logout()
    {
        // return 'if';
        $user = Auth::guard('admin')->user();   
        // $user->last_logout = strtotime('now');
        // $user->save();
        Auth::guard('admin')->logout();
        return redirect('/admin');
    }


    public function login(Request $request)
    {
        $input = $request->all();
        $validator = $this->validator($input);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } else {
            $email = $input['username'];
            $password = $input['password'];
            $remember =  $request->has('remember') ? true : false;
            $attempt = Auth::guard('admin')->attempt(['email' => $email, 'password' => $password], $remember);
            if ($remember == 'true') {
                setcookie('password', $password, time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie('email', $email, time() + (86400 * 30), "/"); // 86400 = 1 day
            } else {
                setcookie('password', '', time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie('email', '', time() + (86400 * 30), "/"); // 86400 = 1 day
            }
            if ($attempt) {
                $user = Auth::guard('admin')->user();   
                $user->last_login = date('Y-m-d H:i:s');
                $user->save();
                $request->session()->flash('alert', 'You are logged in successfully!');
                return redirect()->intended($this->redirectTo);
            } else {
                $message = 'You have entered wrong email id or Password.';
                return redirect()->back()->withInput()->with(['error' => $message]);
            }
        }
    }


    public function sendResetLinkEmail(Request $request){
        $user = Admin::where('email', $request->email)->first();

        if ( !$user ) 
            return redirect()->back()->with(['error' => 'Enter valid email']);

            
        $email = $request->email;
            $token = time().$this->generateRandomString(30);
            $reset_password = DB::table('password_resets')->where('email', $email)->first();
            if($reset_password){
                DB::table('password_resets')->where('email', $email)->update([
                    'token' => $token, //change 60 to any length you want
                ]);
            }else{
                DB::table('password_resets')->insert([
                    'email' => $email,
                    'token' => $token, //change 60 to any length you want
                    'created_at' => Carbon::now()
                ]);
            }
            $data = array(
                'email'=> $email,
                'name' => $user['name'],
                'token'=> $token,
                'link' => url('password/reset/'.$token),
            );
            //send mail to reset password
            Mail::send('email.forget_password', $confirmed = array('user_info'=>$data), function($message ) use ($email){
                // $message->to($email)->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))->subject('Forgot Password');
                $message->to($email)->from('softuvotest@gmail.com', 'Eaglei')->subject('Forgot Password');
            });
            \Session::flash('success','Password Reset Link Send to your Email.');
            return Redirect('/admin/login');

    }


    public function password_reset(Request $request)
    {
        $rd = $request->all();
        $rd['email'] = strtolower($request->input('email'));
        $request->replace($rd);
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'email' => 'required|string|email',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } else {
            


            $token = DB::table('password_resets')->where('email', $request->email)->first();
            if ($token !== '') {
                $email = $token['email'];
                $user = Admin::where('email', $email)->first();
                if ($user !== '') {

                    $user->password = Hash::make($request->get('password'));

                    //DB::table('password_resets')->where('email', $user->email)->delete();

                    $data['name'] = $user->name;
                    $user->save();
                    Mail::send('email.password', $confirmed = array('user_info' => $data), function ($message) use ($email) {
                        $message->to($email)->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))->subject('Password Changed');
                    });
                    $message = 'Your password updated successfully.';
                    return redirect('admin/login')->with(['success' => $message]);
                } else {
                    $message = 'User not found.';
                    return redirect('admin/login')->with(['error' => $message]);
                }
            } else {
                $message = 'Something went wrong. Please contact to admin';
                return redirect('admin/login')->with(['error' => $message]);
            }
            return back();
        }
    }

}
