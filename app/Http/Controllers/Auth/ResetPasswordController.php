<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use \App\Models\UserPasswordReset;
use Illuminate\Http\Request;
use \App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use Mail;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function showResetForm($token = null, Request $request)
    {
        $tokens = UserPasswordReset::where('token', $token)->first();
        
        if (!$tokens) {
            \Session::flash('error', 'Token expired !');
            return Redirect('admin/login');
        }
        $email = User::where('id', $tokens['user_id'])->pluck('email')->first();
        return view('auth.passwords.reset', compact('token', 'email'));
    }

    public function password_reset(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'email' => 'required|string|email',
            // 'password' => 'required|confirmed',
            
        ]);

        $user = User::where('email', $request->email)->first();
        
        if ( !$user ) 
            return redirect()->back()->with(['error' => 'Enter valid email']);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } 
        
        $token = DB::table('user_password_reset')->where('user_id', $user['id'])->first();
        
        if ($token !== '') {
            $email = $user['email'];
            $user->password = Hash::make($request->get('password'));
            $data['name'] = $user->name;
            $user->save();
            Mail::send('email.password_success', $confirmed = array('user_info' => $data), function ($message) use ($email) {
                $message->subject('Password Changed')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->to('testsoftuvo@gmail.com');
                // ->to($email);
            });
            $message = 'Your password updated successfully.';
            UserPasswordReset::where('id', $token->id)->delete(); 
            return redirect('login')->with(['success' => $message]);
           
        }
        $message = 'Something went wrong. Please contact to admin';
        return redirect('login')->with(['error' => $message]);
            
        
    }

}
