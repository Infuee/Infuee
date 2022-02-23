<?php

namespace App\Helpers;
use App\Models\Setting;
use App\Models\Rating;
use App\Models\Job;
use App\Models\JobReviews;
use App\Models\JobProposals;
use App\Models\Notification;
use App\Models\UserPlans;
use App\Models\Messages;
use App\Models\MessageReadMark;
use App\Models\ChatRoomPartcipants;
use App\Models\SocialPlatform;
use App\User;
use File;
use Request;
use DB;


class Helpers
{

    public static function encrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = '{#>sD~k2Ej:-eC7{TASvNj1a@`e`H+8=T?U&Kbl2BdB~QO<:&uRVypzqR#Yrb$^n';
        $secret_iv = 'yVu 2i-M%c}n^.Z_9nj$rsBKhUl&O[nU_uWi]ntX$$t4![DH5{m( P;q,`VhucOn';
        
        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    
    public static function ageFilters(){
        
        $array = [
            1 => '0 - 10',
            2 => '11 - 20',
            3 => '21 - 30',
            4 => '31 - 40',
            5 => '41 - 50',
            6 => '51+',
        ];
        
        return $array ;
    }
    
    public static function decrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = '{#>sD~k2Ej:-eC7{TASvNj1a@`e`H+8=T?U&Kbl2BdB~QO<:&uRVypzqR#Yrb$^n';
        $secret_iv = 'yVu 2i-M%c}n^.Z_9nj$rsBKhUl&O[nU_uWi]ntX$$t4![DH5{m( P;q,`VhucOn';
        
        // hash
        $key = hash('sha256', $secret_key);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }

    public static function get_settings()
    {   
        $data = Setting::first();
        return $data;
    }

    public static function get_ratings_average($id)
    {   
        $totalUsers = Rating::where('influencer_id',$id)->where('rating','>',0)->count('id');
        $totalRating = Rating::where('influencer_id',$id)->sum('rating');
        if($totalUsers > 0){
            $avg = $totalRating / $totalUsers;
            $avg = number_format((float)$avg, 1, '.', '');
        }else{
            $avg = 0;
        }
        $data['average'] = $avg;
        $data['total_users'] = $totalUsers;
        $data['totalRating'] = $totalRating;
        return $data;
    }

    public static function asset($path){

        $path = trim($path);
        if($path[0] !== '/'){
            $path = '/'.$path ;
        }
        
        if(strpos("?", $path) === false ){
            $temp = '?v=' ;
        }else{
            $temp = '&v=' ;
        }
        
        if (File::exists(public_path().$path)) {
            return $path.$temp.filemtime(public_path().$path) ;
        }
        
        return asset($path);
    }

    public static function isMenuActive($menu){
        $segment1 = Request::segment(1) ;
        $segment2 = Request::segment(2) ;
        $segment3 = Request::segment(3) ;
        switch($menu){
            case 'campaigns' :
                if($segment1 == 'campaigns' || $segment1 == 'campaign'){
                    return 'active';
                }
                if(($segment1 == 'edit' || $segment1 == 'create' )&& $segment2 == 'campaign'){
                    return 'active';
                }
                break;
            case 'jobs':
                if($segment1 == 'jobs' || $segment1 == 'job'){
                    return 'active';
                }
                break;


        }

    }

    public static function timeago($timestamp) {
       
       $strTime = array("sec", "min", "h", "day", "month", "year");
       $length = array("60","60","24","30","12","10");

       $currentTime = time();
       if($currentTime >= $timestamp) {
            $diff     = time()- $timestamp;
            for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
            $diff = $diff / $length[$i];
            }

            $diff = round($diff);

            if(!$diff)
                return 'now';

            return $diff . " " . $strTime[$i] . ($diff > 1 ? 's' : '')." ago ";
       }
    }

    public static function checkJobStatus($jobId) {
        $job = Job::select('status')->where('id', $jobId )->first();  
        return $job->status; 
    }

    public static function avgRateingJobs($jobId) {
        $totalUsers = JobReviews::where('order_id', $jobId)->where('rating','>',0)->count('id');
        $jobsReviewRateing = JobReviews::where('order_id', $jobId)->sum('rating');
        
        if($totalUsers > 0){
            $avg = $jobsReviewRateing / $totalUsers;
            $avg = number_format((float)$avg, 1, '.', '');
        }else{
            $avg = 0;
        }


        $data['average'] = $avg;
        $data['total_users'] = $totalUsers;
        //$data['totalRating'] = $jobsReviewRateing;
        $data['totalRating'] = $avg;
        return $data;
    }

    public static function checkUserReviews($jobId){
        $totalUsers = JobReviews::where('order_id', $jobId)->where('rated_by',auth()->user()->id)->count();
        return $totalUsers;
    }

    public static function checkWalletforHire(){
        $userid = auth()->user()->id;
        $wallet = \App\Models\UserWallet::where('user_id', $userid)->first();
        /*$runningJobCost = JobProposals::where('job_created_by', $userid)
        ->where('influencers_hire_status', 0)
        ->sum('cost');*/
        $runningJobCost = JobProposals::where('job_created_by', $userid)
        ->select('cost')
        ->first();


        if(@$wallet['amount'] > @$runningJobCost['cost']){
            return 1;
        } else {
            return 0; //show wallet amount message
        }


    }
    public static function getUserPlan( $userId ) {
        $planPrice = UserPlans::select('price')
        ->where('user_id', $userId)
        ->orderBy('price','DESC')
        ->first();
        
        return @$planPrice['price']?:'0'; 
    }

    public static function notificationCount(){
        $getAllNotificatios = Notification::where('user_id',auth()->user()->id)->where('seen', '0')->count();
        return $getAllNotificatios;
    }

     public static function dateDifferenceTime($jobId){
        $jobData =  Job::select('created_at')->where('id' , $jobId)->first();
        return $jobData->created_at ? $jobData->created_at->diffForHumans() : ' '; 
    }

    public static function  userCount($category_id,$platformName){
        $Count=DB::table('users')
           ->join('user_plans','user_plans.user_id','=','users.id')
           ->join('user_platform_stats','user_platform_stats.user_id','=','users.id')
           ->where('user_platform_stats.platform_id',$platformName)
           ->where('users.category',$category_id)
           ->where('users.type','2')
           ->where('users.account_verified','1')
           ->groupBy('user_plans.user_id')
           ->distinct()->count('user_plans.user_id');
        return $Count;
    }

    public static function getRoomParticpantId( $chatroomid ){
        $partcipantId = ChatRoomPartcipants::where('user_id', auth()->user()->id )
        ->where('chat_room_id', $chatroomid)
        ->select('id')
        ->first();
        return $partcipantId['id'];
    }


    public static function unReadMsgCount($chatroomId, $userParticpant){
       /* $recevierMsg = ChatRoomPartcipants::where('chat_room_id', $chatroomId) 
        ->where('id', '!=', $userParticpant)
        ->first();*/
        //dd($chatroomId);

        $unreadMsg = MessageReadMark::where('chat_room_id',$chatroomId)
                    ->where('participant_id',$userParticpant)
                    ->where('seen_status', 0)
                    ->count('id');
       
        return $unreadMsg?:'';

    }

    

    public static function avgRateing($influencer_id){

          $rating_average=JobReviews::where(['rated_by' => $influencer_id])
          ->avg('rating');
          $totalUsers=JobReviews::where(['rated_by' => $influencer_id])
          ->count();
          $avg=round($rating_average,'1');
          $html="";
            $data['average'] = $avg;
            $data['total_users'] = $totalUsers;
            $data['totalRating'] = $rating_average;

            return  $data;
 
    } 

    public static function platformCategorey($plateformsName){
    $data=SocialPlatform::where('id',$plateformsName )->where('status','1')->first();
    return $data;
    }
    
    public static function workHistory($workhistory){
        $html="";
       if($workhistory==1){
            $html='<i class="fas fa-star"></i>
            <i class="fas fa-star last-star"></i>
            <i class="fas fa-star last-star"></i>
            <i class="fas fa-star last-star"></i>
            <i class="fas fa-star last-star"></i>';
          }
          if($workhistory==2){
            $html='<i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star last-star"></i>
            <i class="fas fa-star last-star"></i>
            <i class="fas fa-star last-star"></i>';
          }
           if($workhistory==3){
            $html='<i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star last-star"></i>
            <i class="fas fa-star last-star"></i>';
          }
           if($workhistory==4){
            $html='<i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star last-star"></i>';
          }
          if($workhistory==5){
            $html='<i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>';
          }
          return $html;
    }


    public static function isWebview(){
        if ((strpos(@$_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos(@$_SERVER['HTTP_USER_AGENT'], 'Safari/') == false)) {
            return true;
        } 

        if (@$_SERVER['HTTP_X_REQUESTED_WITH'] == "com.softuvo.infuee_flutter" || @$_SERVER['HTTP_X_REQUESTED_WITH'] == 'com.android.infuee' || @$_SERVER['HTTP_X_REQUESTED_WITH'] == 'com.infuee') {
            return true;
        }
        return false ;
    }

    
}
