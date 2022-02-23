<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\User;
use Validator;
use Socialite;
use Exception;
use Helpers ;
use Auth;
use Session;
use App\Models\InfluencerRequests;
use GuzzleHttp\Client;
use App\Models\UserPlatformStats ;
use App\Models\SocialPlatform ;
use Illuminate\Support\Facades\Hash;
use URL;
class SocialMediaController extends Controller
{
    public function redirectToFacebook(Request $request)
    {
        $type = $request->get('type');
        if(\Request::segment(1) == 'verify'){
            if(!empty($type)){
            config(['services.facebook.redirect' => url('verify/facebook/callback?type='.$type) ]);
            }else{
            config(['services.facebook.redirect' => url('verify/facebook/callback') ]);
            }
    }

        return Socialite::driver('facebook')->scopes(['email'])->redirect();
    }

    public function facebookSignin(Request $request)
    {  
        $type = $request->get('type');
        try {
            if(\Request::segment(1) == 'verify'){
                config(['services.facebook.redirect' => url('verify/facebook/callback') ]);
            }
            $user = Socialite::driver('facebook')->fields(['id', 'email', 'cover', 'name', 'first_name', 'last_name', 'age_range', 'link', 'gender', 'locale', 'picture', 'timezone', 'updated_time', 'verified', 'birthday', 'friends', 'relationship_status', 'significant_other'])->user();    
            
            if(\Request::segment(1) == 'verify'){
                $eUser = User::where('id', auth()->id() )->first();
            }else{
                $eUser = User::whereEmail($user->email)->first();
            }
            $exists = UserPlatformStats::where('platform_social_id', $user->id)->where('platform_id', SocialPlatform::FACEBOOK)->where('user_id' ,'!=', auth()->id())->first();
            if(!empty($exists)){
                Session::flash('error', "This facebook account linked to other influencer. Please try with other accout"); 
                if(\Request::segment(1) == 'verify'){
                    return redirect('be-influencer'); 
                }
                return redirect('login');
            }
            if(empty($eUser)){
                $eUser = new User();
                $eUser->email = $user->email;

    		    if( !$user->email ){
    			    Session::flash('error', "We need email address to process your signin/signup request. Please give permission to email address from your facebook account"); 
    	            if(\Request::segment(1) == 'verify'){
                        return redirect('be-influencer');
            	    }
    	            return redirect('login');
    		    }
                $eUser->first_name = $user->user['first_name'];
                $eUser->last_name = $user->user['last_name'];
                $eUser->username = str_replace(' ', '', $user->name) .''. rand(100, 999) ;
                $eUser->email_verified_at = date('Y-m-d H:i:s');
                $eUser->account_verified = 1 ;
                $eUser->status = User::STATUS_ACTIVE ;
                $eUser->password = Hash::make($eUser->first_name.'@1234');
                $eUser->type = User::TYPE_USER ;
                $eUser->phone = '' ;
                $eUser->save();
            }

            $socialStats = UserPlatformStats::where('user_id', $eUser->id)->where('platform_id', SocialPlatform::FACEBOOK )->first();
            if(empty($socialStats)){
                $socialStats = new UserPlatformStats();
                $socialStats->user_id = $eUser->id ;
                $socialStats->platform_id = SocialPlatform::FACEBOOK ;
            }
            
            $socialStats->username = $eUser->username ;
            $socialStats->followers = 0 ;
            $socialStats->platform_social_id = $user->id ;
            $socialStats->save();

            if(\Request::segment(1) == 'verify'){
                //$eUser->type = User::TYPE_INFLUENCER ;
                $eUser->save();
                $this->sendRequest($eUser);
                Session::flash('success', "Facebook account linked successfully. You became influencer now.");
                if(!empty($type)){
                    return redirect('my-profile');
                }
                return redirect('be-influencer');
            }

            Auth::login($eUser);
            return redirect('/');

      } catch (Exception $exception) {
           Session::flash('error', $request->error_message); 
           return redirect('login');
      }
    }

    public function redirectToYoutube(Request $request)
    {
         $type = $request->get('type');
       

        if(\Request::segment(1) == 'verify'){
            if(!empty($type)){
            config(['services.youtube.redirect' => url('verify/youtube/callback?type='.$type) ]);
          }else{
            config(['services.youtube.redirect' => url('verify/youtube/callback')]);
          }
        }
      
       
        return Socialite::driver('youtube')->scopes(['openid','profile','email','https://www.googleapis.com/auth/youtube.readonly'])->redirect();
    }

    public function youtubeSignin(Request $request, $type = false)
    {
            $type = $request->get('type');
        try {
            if(\Request::segment(1) == 'verify'){
                config(['services.youtube.redirect' => url('verify/youtube/callback') ]);
            }
            $user = Socialite::driver('youtube')->user();
            
            $chennelId = @$user->user['id']?:@$user->user['sub'] ;
            $apiKey = env('YOUTUBE_API_KEY') ; 
            $client = new Client();
            
            $response = $client->request('GET', "https://www.googleapis.com/youtube/v3/channels?part=statistics&id={$chennelId}&key={$apiKey}");
            $content = $response->getBody()->getContents();
            $oAuth = json_decode($content);

            if(\Request::segment(1) == 'verify'){
                $eUser = User::where('id', auth()->id() )->first();
            }else{
                $eUser = User::whereEmail( $user->email )->first();
            }

            $exists = UserPlatformStats::where('platform_social_id', $chennelId)->where('platform_id', SocialPlatform::YOUTUBE)->where('user_id' ,'!=', auth()->id())->first();
            if(!empty($exists)){
                Session::flash('error', "This youtube account linked to other influencer. Please try with other accout"); 
                if(\Request::segment(1) == 'verify'){
                    return redirect('be-influencer'); 
                }
                return redirect('login');
            }
            
            if(!isset($oAuth->items)){
                Session::flash('error', "There is no youtube channel found with this google account. Please select with other valid youtube account."); 
                if(\Request::segment(1) == 'verify'){
                    return redirect('be-influencer'); 
                }
                return redirect('login');
            }

            $data = $oAuth->items ;

            if(empty($eUser)){
                $eUser = new User();
                $eUser->email = $user->email;
                $eUser->first_name = $user->user['given_name'];
                $eUser->last_name = $user->user['family_name'];
                $eUser->username = str_replace(' ', '', $user->name) .''. rand(100, 999) ;
                $eUser->email_verified_at = date('Y-m-d H:i:s');
                $eUser->account_verified = 1 ;
                $eUser->status = User::STATUS_ACTIVE ;
                $eUser->password = Hash::make($eUser->first_name.'@1234');
                $eUser->type = User::TYPE_USER ;
                $eUser->phone = '' ;
                $eUser->save();
            }

            $socialStats = UserPlatformStats::where('user_id', $eUser->id)->where('platform_id', SocialPlatform::YOUTUBE)->first();
            if(empty($socialStats)){
                $socialStats = new UserPlatformStats();
                $socialStats->user_id = $eUser->id ;
                $socialStats->platform_id = SocialPlatform::YOUTUBE ;
            }
            
            $socialStats->username = $eUser->username ;
            $socialStats->followers = @$data[0]->statistics->subscriberCount ? : 0 ;
            $socialStats->platform_social_id = $chennelId ;
            $socialStats->save();
            if(\Request::segment(1) == 'verify'){
                //$eUser->type = User::TYPE_INFLUENCER;
                $eUser->save();
                $this->sendRequest($eUser);
                Session::flash('success', "Youtube account linked successfully. You became influencer now.");

                    if(!empty($type)){
                    return redirect('my-profile');
                    }else{
                    return redirect('be-influencer');
                   } 
            }
            Auth::login($eUser);
            return redirect('/');

        } catch (Exception $exception) {
            Session::flash('error', @$request->error_message); 
            return redirect('login');
        }
    }

    public function redirectToInstagram(Request $request)
    {   
        $type = $request->get('type');
        $appId = config('services.instagram.client_id');
        if(\Request::segment(1) == 'verify'){
            if(!empty($type)){
                $redirectUri = url('verify/instagram/callback?type='.$type);
            }else{
                $redirectUri = url('verify/instagram/callback');
            }
        }else{
            $redirectUri = urlencode(config('services.instagram.redirect'));
        }
        
        return Socialite::driver('instagram')->setScopes(['user_profile'])->redirect();

        return redirect()->to("https://api.instagram.com/oauth/authorize?app_id=".$appId."&scope=basic&response_type=code&redirect_uri=".$redirectUri);
    }

    public function instagramSignin(Request $request)
    {
        $type = $request->get('type');
        // try{

            // $data = \App\User::getInstaDetails('infueeapp');
            $code = $request->code;
            if (empty($code)) return redirect()->route('home')->with('error', 'Failed to login with Instagram.');

            $appId = config('services.instagram.client_id');
            $secret = config('services.instagram.client_secret');
            if(\Request::segment(1) == 'verify'){
                $redirectUri = url('verify/instagram/callback');
            }else{
                // $redirectUri = urlencode(config('services.instagram.redirect'));
                $redirectUri = config('services.instagram.redirect');
            }

            $client = new Client();

            // Get access token
            $response = $client->request('POST', 'https://api.instagram.com/oauth/access_token', [
                'form_params' => [
                    'app_id' => $appId,
                    'app_secret' => $secret,
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $redirectUri,
                    'code' => $code,
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                return redirect()->route('home')->with('error', 'Unauthorized login to Instagram.');
            }

            $content = $response->getBody()->getContents();
            $content = json_decode($content);
            
            $accessToken = $content->access_token;
            $userId = $content->user_id;

            $response = $client->request('GET', "https://graph.instagram.com/me?fields=id,username,email&access_token={$accessToken}");

            $content = $response->getBody()->getContents();
            $oAuth = json_decode($content);
            
            if(\Request::segment(1) == 'verify'){
                $eUser = User::where('id',auth()->id() )->first();
            }else{
                $eUser = User::where('username',$oAuth->username)->first();
            }

            if(empty($eUser)){
                $eUser = new User();
                $eUser->email = @$oAuth->email?:$userId;
                $eUser->first_name = $oAuth->username;
                // $eUser->last_name = $user->user['family_name'];
                $eUser->username = $oAuth->username;
                $eUser->email_verified_at = date('Y-m-d H:i:s');
                $eUser->account_verified = 1 ;
                $eUser->status = User::STATUS_ACTIVE ;
                $eUser->password = Hash::make($eUser->username.'@1234');
                $eUser->type = User::TYPE_USER ;
                $eUser->phone = '' ;
                $eUser->save();
            }

            $socialStats = UserPlatformStats::where('user_id', $eUser->id)->where('platform_id', SocialPlatform::INSTAGRAM)->first();
            if(empty($socialStats)){
                $socialStats = new UserPlatformStats();
                $socialStats->user_id = $eUser->id ;
                $socialStats->platform_id = SocialPlatform::INSTAGRAM ;
            }
            
            $socialStats->username = $eUser->username ;
            $socialStats->followers = 0 ;
            $socialStats->platform_social_id = $oAuth->id ;
            $socialStats->save();
            if(\Request::segment(1) == 'verify'){
                //$eUser->type = User::TYPE_INFLUENCER ;
                $eUser->save();
                $this->sendRequest($eUser);
                Session::flash('success', "Instagram account linked successfully. You became influencer now.");
                if(!empty($type)){
                return redirect('my-profile');
                } else{
                return redirect('be-influencer');
                }
            }
            Auth::login($eUser);
            return redirect('/');
        // } catch (Exception $exception) {
        //     Session::flash('error', @$request->error_message); 
        //     return redirect('login');
        // }
    }

    public function redirectToTwitter()
    {   
        try{
            if(\Request::segment(1) == 'verify'){
                config(['services.twitter.redirect' => url('verify/twitter/callback') ]);
            }else{
                config(['services.twitter.redirect' => url('auth/twitter/callback') ]);
            }

            return Socialite::driver('twitter')->redirect();
        }
        catch (\Illuminate\Contracts\Container\BindingResolutionException $e)
        {
           return redirect()->back()->withInput()->with(['error' => "Authentication with twitter is not available. Please try with another social logins." ]);
        }
        catch (\Exception $e)
        {
           return redirect()->back()->withInput()->with(['error' => "Authentication with twitter is not available. Please try with another social logins." ]);
        }
            
    }
    

    public function twitterSignin()
    {
        try {
        
            $user = Socialite::driver('twitter')->user();
            
            if(\Request::segment(1) == 'verify'){
                $eUser = User::where('id', auth()->id() )->first();
            }else{
                $eUser = User::whereEmail($user->email)->first();
            }
            
            $exists = UserPlatformStats::where('platform_social_id', $user->id)->where('platform_id', SocialPlatform::TWITTER)->where('user_id' ,'!=', auth()->id())->first();
            
            if(!empty($exists)){
                Session::flash('error', "This twitter account linked to other influencer. Please try with other accout"); 
                if(\Request::segment(1) == 'verify'){
                    return redirect('be-influencer'); 
                }
                return redirect('login');
            }
            
            if(empty($eUser)){
                $eUser = new User();
                $eUser->email = $user->email;

                if( !$user->email ){
                    Session::flash('error', "We need email address to process your signin/signup request. Please give permission to email address from your twitter account"); 
                    if(\Request::segment(1) == 'verify'){
                        return redirect('be-influencer');
                    }
                    return redirect('login');
                }
                $eUser->first_name = $user->name;
                $eUser->last_name = $user->nickname;
                $eUser->username = str_replace(' ', '', $user->name) .''. rand(100, 999) ;
                $eUser->email_verified_at = date('Y-m-d H:i:s');
                $eUser->account_verified = 1 ;
                $eUser->status = User::STATUS_ACTIVE ;
                $eUser->password = Hash::make($eUser->first_name.'@1234');
                $eUser->type = User::TYPE_USER ;
                $eUser->phone = '' ;
                $eUser->save();
            }

            $socialStats = UserPlatformStats::where('user_id', $eUser->id)->where('platform_id', SocialPlatform::TWITTER )->first();
            if(empty($socialStats)){
                $socialStats = new UserPlatformStats();
                $socialStats->user_id = $eUser->id ;
                $socialStats->platform_id = SocialPlatform::TWITTER ;
            }
            
            $socialStats->username = $eUser->username ;
            $socialStats->followers = 0 ;
            $socialStats->platform_social_id = $user->id ;
            $socialStats->save();

            if(\Request::segment(1) == 'verify'){
                //$eUser->type = User::TYPE_INFLUENCER ;
                $eUser->save();
                $this->sendRequest($eUser);
                Session::flash('success', "Twitter account linked successfully. You became influencer now.");
                if(!empty($type)){
                    return redirect('my-profile');
                }
                return redirect('be-influencer');
            }

            Auth::login($eUser);
            return redirect('/');

        } catch (Exception $exception) {
            return redirect()->back()->withInput()->with(['error' => "Authentication with tiktok is not available. Please try with another social logins." ]);
        }
    }


    public function redirectToTiktok()
    {
        try {
            $appId = env('TIKTOK_API_KEY');
        
            if(\Request::segment(1) == 'verify'){
                $redirectUri = urlencode(url('verify/tiktok/callback'));
            }else{
                $redirectUri = urlencode(env('TIKTOK_REDIRECT_URL'));
            }

            return redirect()->to("https://open-api.tiktok.com/platform/oauth/connect?client_key={$appId}&response_type=code&scope=user.info.basic,video.list&redirect_uri={$redirectUri}&state=123321123&error_uri={$redirectUri}");
        
        } catch (Exception $exception) {
            return redirect()->back()->withInput()->with(['error' => "Authentication with tiktok is not available. Please try with another social logins." ]);
        }
    }

    public function tiktokSignin(Request $request)
    {
        try {
        
            $code = $request->code;
            if (empty($code)) return redirect()->route('home')->with('error', 'Failed to login with tiktok.');
        
            $appId = env('TIKTOK_API_KEY');
            $secret = env('TIKTOK_CLIENT_SECRET');

            $client = new Client();
            $response = $client->post("https://open-api.tiktok.com/oauth/access_token?client_key={$appId}&client_secret={$secret}&code={$code}&grant_type=authorization_code");

            $content = $response->getBody()->getContents();
            $data = json_decode($content);
            
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://open-api.tiktok.com/user/info/');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"access_token\": \"".$data->data->access_token."\",\"open_id\": \"".$data->data->open_id."\",\"fields\": [\"open_id\", \"union_id\", \"avatar_url\", \"display_name\"]}");

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);

            $data = json_decode($result);
            $user = $data->data->user ;
            $exists = UserPlatformStats::where('platform_social_id', $user->open_id)->where('platform_id', SocialPlatform::TIKTOK)->where('user_id' ,'!=', auth()->id())->first();
            
            if(\Request::segment(1) == 'verify'){
                $eUser = User::where('id', auth()->id() )->first();
            }else{
                $socialStats = UserPlatformStats::where('platform_social_id', $user->open_id)->where('platform_id', SocialPlatform::TIKTOK )->first();
                $eUser = User::where('id', @$socialStats['user_id'])->first();
            }

            if(!empty($exists)){
                Session::flash('error', "This tiktok account linked to other influencer. Please try with other accout"); 
                if(\Request::segment(1) == 'verify'){
                    return redirect('be-influencer'); 
                }
                return redirect('login');
            }
            
            if(empty($eUser)){
                $eUser = new User();
                // $eUser->email = $user->email ;

                $eUser->first_name = $user->display_name;
                //$eUser->last_name = $user->nickname;
                $eUser->username = str_replace(' ', '', $user->display_name) .''. rand(100, 999) ;
                $eUser->email_verified_at = date('Y-m-d H:i:s');
                $eUser->account_verified = 1 ;
                $eUser->image =  $user->avatar_url ;
                $eUser->status = User::STATUS_ACTIVE ;
                $eUser->password = Hash::make($eUser->first_name.'@1234');
                $eUser->type = User::TYPE_USER ;
                $eUser->phone = '' ;
                $eUser->save();
            }

            $socialStats = UserPlatformStats::where('user_id', $eUser->id)->where('platform_id', SocialPlatform::TIKTOK )->first();
            if(empty($socialStats)){
                $socialStats = new UserPlatformStats();
                $socialStats->user_id = $eUser->id ;
                $socialStats->platform_id = SocialPlatform::TIKTOK ;
            }
            
            $socialStats->username = $eUser->username ;
            $socialStats->followers = 0 ;
            $socialStats->platform_social_id = $user->open_id ;
            $socialStats->save();

            if(\Request::segment(1) == 'verify'){
                //$eUser->type = User::TYPE_INFLUENCER ;
                $eUser->save();
                $this->sendRequest($eUser);
                Session::flash('success', "Tiktok account linked successfully. You became influencer now.");
                if(!empty($type)){
                    return redirect('my-profile');
                }
                return redirect('be-influencer');
            }

            Auth::login($eUser);
            return redirect('/');


        } catch (Exception $exception) {
            return redirect()->back()->withInput()->with(['error' => "Authentication with tiktok is not available. Please try with other social logins." ]);
        }
    }

    public function sendRequest($user){

        if($user['type'] == 2){
            return false ;
        } 
        $isExists = InfluencerRequests::where('user_id', $user['id'])->first();
        
        if(!empty($isExists) && $isExists->status == 0){
            return false ;
        }
        if(!empty($isExists) && $isExists->status == 2){
            return false ;
        }
        
        $raw = [
            'user_id' => $user->id,
            'account_verified' => 1,
            'status' => 1
        ];
        InfluencerRequests::create($raw) ;

        //User account verified
        $user = User::find($user->id);
        $user->account_verified = 1;
        $user->type = 2;
        $user->save();

    }

    
}
