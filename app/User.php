<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Categories;
use App\Models\UserPlans;
use App\Models\Countries;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

// class User extends Authenticatable
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;

    const STATUS_PENDING = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DEACTIVATED = 3;
    const STATUS_BAN = 4;
    
    const TYPE_USER = 1;
    const TYPE_INFLUENCER = 2;

    private $act_url         = 'https://api.instagram.com/oauth/access_token'; 
    private $ud_url         = 'https://api.instagram.com/v1/users/self/'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'phone', 'followers', 'status', 'type', 'image', 'country', 'country_code', 'city', 'state', 'school', 'date_of_bith', 'category','race_id', 'account_verified', 'otp', 'is_two_fa', 'address', 'social_links', 'lat', 'lng', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getDateOfBithAttribute($value){
        return $value == "1970-01-01" ? null : $value ;
    }

    public function statusBadge(){
        $array = [
            self::STATUS_PENDING => '<span style="width: 137px;">
                                        <span class="label font-weight-bold label-lg label-light-warning label-inline">Pending</span>
                                    </span>',
            self::STATUS_ACTIVE => '<span style="width: 137px;">
                                        <span class="label font-weight-bold label-lg label-light-success label-inline">Active</span>
                                    </span>',
            self::STATUS_DEACTIVATED => '<span style="width: 137px;">
                                        <span class="label font-weight-bold label-lg label-light-danger label-inline">Deactivated</span>
                                    </span>',
            self::STATUS_BAN => '<span style="width: 137px;">
                                        <span class="label font-weight-bold label-lg label-light-info label-inline">Banned</span>
                                    </span>',
        ];
        if($this->status){
            return isset($array[$this->status]) ? $array[$this->status] : $array;
        }

        return $array;
    }

    public function typeBadge(){
        $array = [
            self::TYPE_USER => '<span style="width: 137px;">
                                        <span class="label font-weight-bold label-lg label-light-danger label-inline">User</span>
                                    </span>',
            self::TYPE_INFLUENCER => '<span style="width: 137px;">
                                        <span class="label font-weight-bold label-lg label-light-success label-inline">Influencer</span>
                                    </span>',
        ];
        if($this->status){
            return isset($array[$this->status]) ? $array[$this->status] : $array;
        }

        return $array;
    }

    public function getProfileImage(){

        if( strpos($this->image, 'https://') !== false ){
            return $this->image;
        }

        if(!empty($this->image)){

            return asset('/media/users/'.$this->image);
        }
        return asset('/media/users/default.jpg');
    }

    public function getFollowers(){
        if ($this->followers > 999 && $this->followers <= 999999) {
            $result = floor($this->followers / 1000) . ' K';
        } elseif ($this->followers > 999999) {
            $result = floor($this->followers / 1000000) . ' M';
        } else {
            $result = $this->followers;
        }

        return $result;
    }

    public function getCategory(){
        $cat = Categories::find($this->category);
        return @$cat['name']?:"";
    }

    public function getPrice($user_id){
        $UserPlans = UserPlans::select('price','id')->where(['user_id'=>$user_id])->where('price','>',0)->orderBy('price', 'asc')->first();
        return @$UserPlans['price'];
    }

    public function isUser(){
        return $this->type == self::TYPE_USER || !$this->account_verified ; 
    }

    public function isInfluencer(){
        return $this->type == self::TYPE_INFLUENCER && $this->account_verified ; 
    }

    public static function getInstaDetails($username){
        $url = "https://instagram.com/".$username.'/?__a=1';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        echo '<pre>';print_r($result); die;
        $output;
        $metaPos = strpos($result, "<meta content=");
        if($metaPos != false)
        {
            $meta = substr($result ,$metaPos,70);
        
            //meghdare followers
            $followerPos = strpos($meta , "Followers");
            $followers = substr($meta , 15 , $followerPos-15);
            /*if(strpos($followers, "m") != false){                 
                $followers = str_replace("m", "",$followers);  
                $followers = (float)$followers;
                $followers = $followers * 1000000;
            }   
            if(strpos($followers, "k") != false){                 
                $followers = str_replace("m", "",$followers);  
                $followers = (float)$followers;
                $followers = $followers * 1000;
            } */          

            $output['followers'] = (int) $followers;

            //meghdare followings
            // $commaPos = strpos($meta , ',');
            $followingPos = strpos($meta, 'Following');
            $followings = substr($meta , $followerPos+10 , $followingPos - ($followerPos+10));
            $output['following'] = (int) $followings;


            //meghdare posts
            $seccondCommaPos = $followingPos + 10;
            $postsPos = strpos($meta, 'Post');
            $posts = substr($meta, $seccondCommaPos , $postsPos - $seccondCommaPos);
            $output['posts'] = (int) $posts;
            
            //image finder
            $imgPos = strpos($result, "og:image");
            $image = substr($result , $imgPos+19 , 200);
            $endimgPos = strpos($image, "/>");
            $finalImagePos = substr($result, $imgPos+19 , $endimgPos-2);
            $finalImagePos = explode('"', $finalImagePos);
            $output['image'] = $finalImagePos[0];

            return $output;
        }
        else
        {
            return null;
        }
    

    }
 
    public static function getAccessToken($code) {
        $urlPost = 'client_id='. env('INSTA_APP_ID') . '&client_secret=' . env('INSTA_APP_SECRET') . '&redirect_uri=' . env('CALLBACK_URL') . '&code='. $code . '&grant_type=authorization_code';
        $ch = curl_init();         
        curl_setopt($ch, CURLOPT_URL, env('ACT_URL'));         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_POST, 1);         
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $urlPost);             
        $data = json_decode(curl_exec($ch), true);     
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);     
        curl_close($ch); 
        return $data;     
    } 


    public static function getUserProfileInfo($access_token) {  
        $url = env('UD_URL').'?access_token=' . $access_token;     
        $ch = curl_init();         
        curl_setopt($ch, CURLOPT_URL, $url);         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);     
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        $data = json_decode(curl_exec($ch), true); 
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);     
        curl_close($ch); 
        // echo '<pre>';print_r($data); die;
        if($data['meta']['code'] != 200 || $http_code != 200){ 
            //throw new Exception('Error : Failed to get user information'); 
        } 

        //var_dump($data['data']); die;
        //return $data['data']; 
    } 

    public function getCountryFlag(){
        // $countries = config('app.countries');
        // $short = array_search($this->country, $countries);
        // if($short){
        //     return strtolower($short).'.png' ;
        // }

        $country = Countries::where('id', $this->country_code)->first();
        if($country){
            return $country->flag;
        }
        return false;
    }

    public function getCountryName(){
        $country = Countries::where('id', $this->country_code)->first();
        if($country){
            return $country->name;
        }
        return false;
    }

    public function countryDetails(){
        return $this->hasOne('App\Models\Countries', 'id', 'country');
    }

    public function userPlans(){
        return $this->hasMany('App\Models\UserPlans', 'user_id', 'id');
    }

    public function minplan(){
        return $this->hasOne('App\Models\UserPlans', 'user_id', 'id')->orderBy('price', 'asc');
    }

    public static function withPlans(){
        $query = new User();
        $plans = UserPlans::pluck('user_id');
        return $query->whereIn('id', $plans);
    }

    public function facebook(){
        $social_links = json_decode($this->social_links);
        if(isset($social_links->facebook)){
            return $social_links->facebook;
        }
        return "";
    }

    public function twitter(){
        $social_links = json_decode($this->social_links);
        if(isset($social_links->twitter)){
            return $social_links->twitter;
        }
        return "";
    }
    public function instagram(){
        $social_links = json_decode($this->social_links);
        if(isset($social_links->instagram)){
            return $social_links->instagram;
        }
        return "";
    }
    public function tiktok(){
        $social_links = json_decode($this->social_links);
        if(isset($social_links->tiktok)){
            return $social_links->tiktok;
        }
        return "";
    }
    public function youtube(){
        $social_links = json_decode($this->social_links);
        if(isset($social_links->youtube)){
            return $social_links->youtube;
        }
        return "";
    }
     public function pinterest(){
        $social_links = json_decode($this->social_links);
        if(isset($social_links->pinterest)){
            return $social_links->pinterest;
        }
        return "";
    }

    public function getSocialPlatformsHTML($platform = 'web'){

        $array = [
            \App\Models\SocialPlatform::YOUTUBE => \Helpers::asset('images/social-icon3.png'),
            \App\Models\SocialPlatform::TWITTER => \Helpers::asset('images/social-icon5.png'),
            \App\Models\SocialPlatform::INSTAGRAM => \Helpers::asset('images/social-icon1.png'),
            \App\Models\SocialPlatform::FACEBOOK => \Helpers::asset('images/social-icon2.png'),
            \App\Models\SocialPlatform::TIKTOK => \Helpers::asset('images/social-icon4.png'),
        ];


        $userPlatforms = \App\Models\UserPlatformStats::where('user_id', $this->id)->get();
        $html = "";
        $app = [];
        $temp = 0 ;
        foreach($userPlatforms as $userPlatform){
            if($platform == 'web'){
                $html .= '<div class="social-profile_pf"><img src="'. $array[$userPlatform['platform_id']] .'" alt=""><p> '. $userPlatform['followers'] .' </p></div>';
            }else{
                $app[$temp]['link'] = asset($array[$userPlatform['platform_id']]);
                $app[$temp]['count'] = @$userPlatform['followers'];
            }
            $temp++ ;
        }
        
        return $platform == 'web' ? $html : $app;
    }

    public static function ageFilter($index = false){
        $array = [
            1 => '0 - 10',
            2 => '11 - 20',
            3 => '21 - 30',
            4 => '31 - 40',
            5 => '41 - 50',
            6 => '51+',
        ];
        if($index){
            return $array[$index];
        }
        return $array ;
    }

    public function getPlatformDetails($platform){

        return \App\Models\UserPlatformStats::where('user_id', $this->id)->where('platform_id', $platform)->first();

    }

    public function walletAmount(){
        $wallet = \App\Models\UserWallet::where('user_id', $this->id)->first();
        if(empty($wallet)){
            $wallet = \App\Models\UserWallet::create(['user_id' => $this->id , 'amount' => 0]);
        }
        return number_format(@$wallet['amount']?:0, 2);
    }


    public function getLocalTime($serverTime)
    {
        $country = \App\Models\Countries::where('id', $this->country)->first();
        if (empty($country)) {
            return date('Y-m-d H:i:s', strtotime($serverTime));
        }
        $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, @$country['ISO_code']);
        $timezone = @$timezone[0];
        if ($timezone) {
            $datetime = new \DateTime($serverTime);
            $la_time = new \DateTimeZone($timezone);
            $datetime->setTimezone($la_time);
            return $datetime->format('Y-m-d H:i:s');
        }
        return date('Y-m-d H:i:s', strtotime($serverTime));
    }


    public function myChatRooms(){

        return \App\Models\ChatRoomPartcipants::where('user_id', $this->id)->pluck('chat_room_id')->toArray();

    }


     

}
