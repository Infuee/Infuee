<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentPages;
use Auth;
use App\User;
use App\Models\UserPlatformStats;
use App\Models\SocialPlatform;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(isset($_GET['code'])){
            $code = $_GET['code'];
            $access = User::getAccessToken($code);
            // echo '<pre>';print_r($access); die;
            if(isset($access['access_token'])){
                $access_token = $access['access_token'];
            }else{
                $request->session()->flash('alert',$access['error_message']);
            }
            if(!empty($access_token) && isset($access_token)){
                User::getUserProfileInfo($access_token);
            }
        }
        $content = ContentPages::select(['home_page','id'])->first();

        $usersTestimonial = User::join('testimonial', 'users.id', '=', 'testimonial.user_id')
            ->where(function($q){
                $q->where('testimonial.status','Approved')->orWhere('testimonial.status', 'Enable');
            })
            ->select('users.*', 'testimonial.*')
            ->get();
          
       

        $INSTAGRAM = SocialPlatform::where('id','1')->where('status','1')->first();
        $FACEBOOK = SocialPlatform::where('id','2')->where('status','1')->first();
        $YOUTUBE = SocialPlatform::where('id','3')->where('status','1')->first();
        $TIKTOK = SocialPlatform::where('id','4')->where('status','1')->first();
        $TWITTER = SocialPlatform::where('id','5')->where('status','1')->first();
        
        $TopInstagramer = DB::table('user_platform_stats')
        ->join('users','users.id','=','user_platform_stats.user_id')
        ->join('jobs_reviews','jobs_reviews.rated_by','=','user_platform_stats.user_id')
        ->join('user_plans','user_plans.user_id','=','user_platform_stats.user_id')
        ->orderBy('jobs_reviews.rating','DESC')
        ->groupBy('users.id')
        ->select('users.image','users.id','jobs_reviews.rating')
        ->where(['platform_id' => '1'])
        ->where('users.type', '2')
        ->where('users.account_verified', '1')
        ->take(5)
        ->get();
        

        $TopFacebook = DB::table('user_platform_stats')
        ->join('users','users.id','=','user_platform_stats.user_id')
        ->join('jobs_reviews','jobs_reviews.rated_by','=','user_platform_stats.user_id')
        ->join('user_plans','user_plans.user_id','=','user_platform_stats.user_id')
        ->orderBy('jobs_reviews.rating','DESC')
        ->groupBy('users.id')
        ->select('users.image','users.id','jobs_reviews.rating')
        ->where(['platform_id' => '2'])
        ->where('users.type', '2')
        ->where('users.account_verified', '1')
        ->take(5)
        ->get();
        

        $TopYouTuber = DB::table('user_platform_stats')
        ->join('users','users.id','=','user_platform_stats.user_id')
        ->join('jobs_reviews','jobs_reviews.rated_by','=','user_platform_stats.user_id')
        ->join('user_plans','user_plans.user_id','=','user_platform_stats.user_id')
        ->orderBy('jobs_reviews.rating','DESC')
        ->groupBy('users.id')
        ->select('users.image','users.id','jobs_reviews.rating')
        ->where(['platform_id' => '3'])
        ->where('users.type', '2')
        ->where('users.account_verified', '1')
        ->take(5)
        ->get();

        $TopTiktok = DB::table('user_platform_stats')
        ->join('users','users.id','=','user_platform_stats.user_id')
        ->join('jobs_reviews','jobs_reviews.rated_by','=','user_platform_stats.user_id')
        ->join('user_plans','user_plans.user_id','=','user_platform_stats.user_id')
        ->orderBy('jobs_reviews.rating','DESC')
        ->groupBy('users.id')
        ->select('users.image','users.id','jobs_reviews.rating')
        ->where(['platform_id' => '4'])
        ->where('users.type', '2')
        ->where('users.account_verified', '1')
        ->take(5)
        ->get();
        
        $TopTwitter = DB::table('user_platform_stats')
        ->join('users','users.id','=','user_platform_stats.user_id')
        ->join('jobs_reviews','jobs_reviews.rated_by','=','user_platform_stats.user_id')
        ->join('user_plans','user_plans.user_id','=','user_platform_stats.user_id')
        ->orderBy('jobs_reviews.rating','DESC')
        ->groupBy('users.id')
        ->select('users.image','users.id','jobs_reviews.rating')
        ->where(['platform_id' => '5'])
        ->where('users.type', '2')
        ->where('users.account_verified', '1')
        ->take(5)
        ->get();
        $home_page_top_section = ContentPages::select(['home_page_top_section'])->first();
        $home_page_middle_section = ContentPages::select(['home_page_middle_section'])->first();
        return view('welcome', compact('content','INSTAGRAM','FACEBOOK','YOUTUBE','TIKTOK','TWITTER','TopInstagramer','TopFacebook','TopYouTuber','TopTiktok','TopTwitter','usersTestimonial','home_page_top_section','home_page_middle_section'));

    }


    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
