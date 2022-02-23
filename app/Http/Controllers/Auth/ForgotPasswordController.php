<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\User;
use DB;
use Mail;

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


    public function  showForgotForm(){
        return view('auth.forgot');
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
        $user = User::where('email', $request->email)->first();
        
        if ( !$user ) 
            return redirect()->back()->withInput()->with(['error' => 'This email is not registered with us.']);
        
        $email = $request->email;
        
        $token = time().$this->generateRandomString(30);
        
        $reset_password = DB::table('user_password_reset')->where('user_id', $user['id'])->first();
        
        if($reset_password){
            DB::table('user_password_reset')->where('user_id', $user['id'])->update([
                'token' => $token, //change 60 to any length you want
            ]);
        }else{
            DB::table('user_password_reset')->insert([
                'user_id' => $user['id'],
                'token' => $token, //change 60 to any length you want
            ]);
        }
        $data = array(
            'email'=> $email,
            'name' => $user['username'],
            'token'=> $token,
            'link' => url('password/reset/'.$token),
        );
        //send mail to reset password
        Mail::send('email.forget_password', $confirmed = array('user_info'=>$data), function($message ) use ($email){
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Forgot Password')
            // ->to('zoesennett@yopmail.com');
            ->to($email);
        });
        \Session::flash('success','Password Reset Link Send to your Email.');
        return Redirect('/login');

    }

}
