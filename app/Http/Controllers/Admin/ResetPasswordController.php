<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;
use App\Models\Admin;
use App\Models\AdminPasswordReset;
use View;
use Mail;
use DB;
use File;
use Crypt;

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

    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest');
    }


    public function showResetForm($token = null, Request $request)
    {
        $tokens = AdminPasswordReset::where('token', $token)->first();
        
        if (!$tokens) {
            \Session::flash('error', 'Token expired !');
            return Redirect('admin/login');
        }
        $email = Admin::where('id', $tokens['user_id'])->pluck('email')->first();
        return view('admin.auth.passwords.reset', compact('token', 'email'));
    }

    public function password_reset(Request $request)
    {
        $rd = $request->all();
        $rd['email'] = strtolower($request->input('username'));
        $request->replace($rd);
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        $user = Admin::where('email', $request->email)->first();
        
        if ( !$user ) 
            return redirect()->back()->with(['error' => 'Enter valid email']);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        } 
        
        $token = DB::table('admin_password_reset')->where('user_id', $user['id'])->first();
        
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
            return redirect('admin/login')->with(['success' => $message]);
           
        }
        $message = 'Something went wrong. Please contact to admin';
        return redirect('admin/login')->with(['error' => $message]);
            
        
    }
}
