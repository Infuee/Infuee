<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;
	const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVATED = 2;
    const STATUS_COMPLETED = 3;
	protected $table = 'my_jobs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id','race_id','title', 'description', 'category_id', 'influencers', 'duration', 'promo_days', 'price', 'image', 'slug', 'status', 'created_by', 'created_to', 'min_age', 'max_age', 'lat', 'lng'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = true;

    public function status($array = false){
        $array = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DEACTIVATED => 'Archived',
            self::STATUS_COMPLETED => "Completed"
        ];

        if($array){
            return $array;
        }


        if($this->status){
            return isset($array[$this->status]) ? $array[$this->status] : $array;
        }

        return $array;
    }

    public function getMinutes(){
        return floor($this->duration / 60) ;
    }

    public function getSeconds(){
        return $this->duration % 60 ;
    }

    public function category(){
        return $this->hasOne('\App\Models\Categories', 'id', 'category_id');
    }
    public function users(){
        return $this->hasOne('\App\User', 'id', 'created_by');
    }

    public function categoriesSpan(){
        return '<span class="badge badge-info">'.@$this->category['name'].'</span>';
    }

    public function getSlugs($value, $delimiter = '-') {
        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value))))), $delimiter));
        if (static::whereSlug($slug)->exists()) {
            
            $slug = $this->incrementSlug($slug);
        }
    
        return $slug;
    }

    public function incrementSlug($slug) {

        $original = $slug;
    
        $count = 2;
    
        while (static::whereSlug($slug)->exists()) {
    
            $slug = "{$original}-" . $count++;
        }
    
        return $slug;
    
    }

    public function platforms(){
        return $this->hasMany('App\Models\JobPlatform', 'job_id', 'id');
    }

    public function platformsArray(){
        return $this->platforms()->pluck('platform_id')->toArray();
    }

    public function logo(){

        //return asset('uploads/job/'.$this->image);
         if(!empty($this->image) && is_file(('uploads/job/'.$this->image))){
            return asset('uploads/job/'.$this->image);
            }else{
            return asset('/media/users/default.jpg');
        }

    }
    public function image_video(){

        // return asset('uploads/job/'.$this->image_video);
         if(!empty($this->image_video)){
            return asset('uploads/job/'.$this->image_video);
            }else{
            return asset('/media/users/default.jpg');
        }

    }

    public function getPlatformsHTML($image = false,$image_video = false){

        $platforms = $this->platformsArray();

        if($image){
            $this->getSocialMediaImage();
        }
        if($image_video){
            $this->getSocialMediaImage();
        }

         $minimum_followers =$this->minimum_followers;
         $minimum_followers = json_decode($minimum_followers,true);
         if(@$minimum_followers['youtube']>1000) {
            $x = round($minimum_followers['youtube']);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $youtube = $x;
            $youtube = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $youtube .= $x_parts[$x_count_parts - 1];
          }else{
            @$youtube=$minimum_followers['youtube'];
          }
          if(@$minimum_followers['twitter']>1000) {
            $x = round($minimum_followers['twitter']);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $twitter = $x;
            $twitter = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $twitter .= $x_parts[$x_count_parts - 1];
          }else{
            @$twitter=$minimum_followers['twitter'];
          }
          if(@$minimum_followers['instagram']>1000) {
            $x = round($minimum_followers['instagram']);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $instagram = $x;
            $instagram = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $instagram .= $x_parts[$x_count_parts - 1];
          }else{
            @$instagram=$minimum_followers['instagram'];
          }
          if(@$minimum_followers['facebook']>1000) {
            $x = round($minimum_followers['facebook']);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $facebook = $x;
            $facebook = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $facebook .= $x_parts[$x_count_parts - 1];
          }else{
            @$facebook=$minimum_followers['facebook'];
          }
          if(@$minimum_followers['tiktok']>1000) {
            $x = round($minimum_followers['tiktok']);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $tiktok = $x;
            $tiktok = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $tiktok .= $x_parts[$x_count_parts - 1];
          }else{
            @$tiktok=$minimum_followers['tiktok'];
          }
         
         $html = '';

        if( in_array(SocialPlatform::YOUTUBE , $platforms)){
           // $html .= '<a href="javascript:;"> <i class="fab fa-youtube"></i> </a>';
            $html .= '<img src="'. \Helpers::asset('images/job-youtube-icon.png') .'" alt="">';
            if(@$youtube > 0){
            $html .= 'Min'.'. '.@$youtube.' Followers';
            }
        }

        if( in_array(SocialPlatform::TWITTER , $platforms)){
            $html .= '<img src="'. \Helpers::asset('images/job-twitter-icon.png') .'" alt="">';
            if(@$twitter > 0){
            $html .= 'Min'.'. '.@$twitter.' Followers';
            }
        }
        if( in_array(SocialPlatform::INSTAGRAM , $platforms)){
            $html .= '<img src="'. \Helpers::asset('images/job-insta-icon.png') .'" alt="">';
            if(@$instagram > 0){
            $html .= 'Min'.'. '.@$instagram.' Followers';
            }
        }
        if( in_array(SocialPlatform::FACEBOOK , $platforms)){
            $html .= '<img src="'. \Helpers::asset('images/job-fb-icon.png') .'" alt="">';
            if(@$facebook > 0){
            $html .='Min'.'. '.@$facebook.' Followers';
            }
        }
        if( in_array(SocialPlatform::TIKTOK , $platforms)){
            $html .= '<img src="'. \Helpers::asset('images/tiktok.png') .'" alt="">';
            if(@$tiktok > 0){
            $html .='Min'.'. '.@$tiktok.' Followers';
            }
        }
        return $html ;

    }

    public function getSocialMediaImage(){
        $platforms = $this->platformsArray();
        $minimum_followers =$this->minimum_followers;
        $minimum_followers = json_decode($minimum_followers,true);
        if(@$minimum_followers['youtube']>1000) {
            $x = round($minimum_followers['youtube']);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $youtube = $x;
            $youtube = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $youtube .= $x_parts[$x_count_parts - 1];
          }else{
            @$youtube=$minimum_followers['youtube'];
          }
          if(@$minimum_followers['twitter']>1000) {
            $x = round($minimum_followers['twitter']);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $twitter = $x;
            $twitter = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $twitter .= $x_parts[$x_count_parts - 1];
          }else{
            @$twitter=$minimum_followers['twitter'];
          }
            if(@$minimum_followers['instagram']>1000) {
            $x = round($minimum_followers['instagram']);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $instagram = $x;
            $instagram = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $instagram .= $x_parts[$x_count_parts - 1];
          }else{
            @$instagram=$minimum_followers['instagram'];
          }
          if(@$minimum_followers['facebook']>1000) {
            $x = round($minimum_followers['facebook']);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $facebook = $x;
            $facebook = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $facebook .= $x_parts[$x_count_parts - 1];
          }else{
            @$facebook=$minimum_followers['facebook'];
          }
          if(@$minimum_followers['tiktok']>1000) {
            $x = round($minimum_followers['tiktok']);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $tiktok = $x;
            $tiktok = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $tiktok .= $x_parts[$x_count_parts - 1];
          }else{
            @$tiktok=$minimum_followers['tiktok'];
          }
        $html = '';

        if( in_array(SocialPlatform::YOUTUBE , $platforms)){
            $html .= '<img src="'. \Helpers::asset('images/job-youtube-icon.png') .'" alt="">'." ";
            $html .= @$youtube ." ";
        }

        if( in_array(SocialPlatform::TWITTER , $platforms)){
            $html .= '<img src="'. \Helpers::asset('images/job-twitter-icon.png') .'" alt="">'." ";
            $html .= @$twitter ." ";
        }
        if( in_array(SocialPlatform::INSTAGRAM , $platforms)){
            $html .= '<img src="'. \Helpers::asset('images/job-insta-icon.png') .'" alt="">'." ";
            $html .= @$instagram ." ";
        }
        if( in_array(SocialPlatform::FACEBOOK , $platforms)){
            $html .= '<img src="'. \Helpers::asset('images/job-fb-icon.png') .'" alt="">'." ";
            $html .= @$facebook ." ";
        }
        if( in_array(SocialPlatform::TIKTOK , $platforms)){
            $html .= '<img src="'. \Helpers::asset('images/tiktok.png') .'" alt="">'." ";
            $html .= @$tiktok ." ";
        }
        return $html ;
    }

    public function isApplied(){
        $user = auth()->user();
        $offer = \App\Models\UserPostUsrl::where(['job_id' => $this->id, 'influencer_id' => $user['id'] ])->first();

        return !empty($offer) ;
    }

    public function getPostLinks($platform_id){
        $user = auth()->user();
        $offer = \App\Models\UserPostUsrl::where(['job_id' => $this->id, 'influencer_id' => $user['id'] , 'platform_id' => $platform_id])->first();

        return !empty($offer)? $offer->url : '' ;   
    }

    public function isCompleted(){
        $user = auth()->user();
        $offer = \App\Models\UserPostUsrl::where(['job_id' => $this->id, 'influencer_id' => $user['id'] , 'status' => 1 ])->first();
        return !empty($offer);
    }

    public function isHired($user_id = false){
        if(!$user_id){
            $user_id = auth()->id();
        }

        $posted = UserPostUsrl::where(['job_id' => $this->id, 'influencer_id' => $user_id])->count() ;

        $hiring = $posted == UserPostUsrl::where(['job_id' => $this->id, 'influencer_id' => $user_id, 'status' => 2])->count() && $posted ;

        if($hiring){
            return true;
        }
        return false;
    }

    public function isFeedbackGiven($user_id = false){
        if(!$user_id){
            $user_id = auth()->id();
        }
        $rated = JobReviews::where(['rated_from' => auth()->id(), 'rated_by' => $user_id, 'order_id' => $this->id])->count() ;

        if($rated){
            return true;
        }
        return false;
    }
 
    public function hires(){
        if($this->created_by == auth()->id()){
            return JobHiring::where('job_id', $this->id)->get();
        }
        return JobHiring::where(['job_id' => $this->id, 'influencers_id' => auth()->id()])->get();;
    }

    public static function ageFilter($index = false){
        $array = [
            1 => '0 - 10',
            2 => '10 - 20',
            3 => '20 - 30',
            4 => '30 - 40',
            5 => '40 - 50',
            6 => '50+',
        ];
        if($index){
            return $array[$index];
        }
        return $array ;
    }
}
