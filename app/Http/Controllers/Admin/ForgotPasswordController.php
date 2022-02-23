<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Mail;
use Redirect;
use Session;
use App\Merchants;

use App\Models\Admin;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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


    public function sendResetLinkEmail(Request $request){
        $user = Admin::where('email', $request->email)->first();
        
        if ( !$user ) 
            return redirect()->back()->with(['error' => 'Enter valid email']);
        
        $email = $request->email;
        
        $token = time().$this->generateRandomString(30);
        
        $reset_password = DB::table('admin_password_reset')->where('user_id', $user['id'])->first();
        
        if($reset_password){
            DB::table('admin_password_reset')->where('user_id', $user['id'])->update([
                'token' => $token, //change 60 to any length you want
            ]);
        }else{
            DB::table('admin_password_reset')->insert([
                'user_id' => $user['id'],
                'token' => $token, //change 60 to any length you want
            ]);
        }
        $data = array(
            'email'=> $email,
            'name' => $user['name'],
            'token'=> $token,
            'link' => url('admin/password/reset/'.$token),
        );
        //send mail to reset password
        Mail::send('email.forget_password', $confirmed = array('user_info'=>$data), function($message ) use ($email){
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Forgot Password')
            // ->to('testsoftuvo@gmail.com');
            ->to($email);
        });
        \Session::flash('success','Password Reset Link Send to your Email.');
        return Redirect('/admin/login');

    }

    public function viewEmail(Request $request){
        return view('email.forget_password');
    }

}
