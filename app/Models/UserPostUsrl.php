<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPostUsrl extends Model
{
    protected $table = 'users_post_link';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_id','influencer_id', 'url', 'platform_id', 'status'
    ];
    

    public function user(){
        return $this->hasOne(\App\User::class, 'id', 'influencer_id'); 
    }

    public function job(){
        return $this->hasOne(\App\Models\Job::class, 'id', 'job_id'); 
    }

    public function getPlatformsHTML($platform_id){
         //return $platform_id;
        $html ="";
        if($platform_id == 1){
          return  $html .= '<img src="'. \Helpers::asset('images/job-insta-icon.png') .'" alt="">';
        }

        if($platform_id == 2){
          return  $html .= '<img src="'. \Helpers::asset('images/job-fb-icon.png') .'" alt="">';
        }
        if($platform_id == 3){
          return  $html .= '<img src="'. \Helpers::asset('images/job-youtube-icon.png') .'" alt="">';
        }
        if($platform_id == 5){
          return  $html .= '<img src="'. \Helpers::asset('images/job-twitter-icon.png') .'" alt="">';
        }
        if($platform_id == 4){
          return  $html .= '<img src="'. \Helpers::asset('images/tiktok.png') .'" alt="">';
        }


  }
}
