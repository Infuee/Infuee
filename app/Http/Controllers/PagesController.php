<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentPages;
use App\Models\Categories;
use App\User;
use App\Models\Faq;
use App\Models\FaqCat;
use App\Models\ContactUs;
use Illuminate\Support\Facades\Validator;
use App\Models\InfluencerRequests;
use App\Models\PlanCategories;
use App\Models\Plans;
use App\Models\UserPlans;
use App\Models\Countries;
use App\Models\Campaign;
use App\Models\SocialPlatform;
use App\Models\UserPlatformStats;
use App\Models\Job;
use App\Models\JobReviews;
use App\Models\Race;
use DB;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use Helpers;

class PagesController extends Controller
{
    public function index()
    {
        $page_title = 'Dashboard';
        $page_description = 'Some description for the page';

        return view('pages.dashboard', compact('page_title', 'page_description'));
    }

    /**
     * Demo methods below
     */

    // Datatables
    public function influencers(Request $request)
    {

        $data = $request->all();
        //dd($data);
        $page_title = 'Influencers';
        $page_description = 'Browse Influencers';
        
        $categories = Categories::where('status', 1)->pluck('name', 'id')->toArray();
        $race = Race::where('status', 1)->pluck('title', 'id')->toArray();
        $platforms = SocialPlatform::where('status', SocialPlatform::STATUS_ACTIVE)->paginate(10);

        $query = User::withPlans()->where('status', User::STATUS_ACTIVE)->where('account_verified', 1)->where('type', User::TYPE_INFLUENCER);
        if($user = auth()->user()){
            $query = $query->where('id', '!=', $user['id']);
        }
        
        $lowPrice = $request->get('price_min')?:0;
        $maxPrice = UserPlans::orderBy('price', 'DESC')->pluck('price')->first();
        $highPrice = $request->get('price_max')?:$maxPrice;
        $maxPriceData = ($maxPrice== null || $maxPrice== '0') ? 1 : $maxPrice;
        $lowPricePer = (int) ((100 * $lowPrice ) / $maxPriceData);
        $highPricePer = (int) ((100 * $highPrice ) / $maxPriceData);
        $radious = 100 ;
        if($search = $request->get('search')){
            $query->where(function($q) use ($search){
                $q->where('first_name', 'LIKE', "%{$search}%")
                ->orWhere('last_name', 'LIKE', "%{$search}%")
                ->orWhere('username', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if($get_category = $request->get('categories')){
            $get_category = array_filter(explode(",", $get_category ));
            
            if(count($get_category)){
                $query = $query->whereIn('category', $get_category);
            }
        }
        if($get_race= $request->get('race')){
            $get_race = array_filter(explode(",", $get_race ));
            
            if(count($get_race)){
                $query = $query->whereIn('race_id', $get_race);
            }
        }
        $select = ['*'];
        if($age = $request->get('age')){
            
            $range = User::ageFilter($age);
            $array = explode( ' - ' , $range);

            $min = @$array[0];
            $max = @$array[1];
            $select[] = \DB::raw("DATE_FORMAT(FROM_DAYS(DATEDIFF(now(), date_of_bith )), '%Y')+0 AS age") ;
            $query = $query->select($select);
            if($min){
                $query = $query->having('age', '>=', $min);
            }
            if($max){
                $query = $query->having('age', '<=', $max);
            }
        }

        $lat = $request->get('lat') ;
        $lng = $request->get('lng') ;
        $radius = $request->get('radious') ;
        if( $lat & $lng ){
            $select[] = \DB::raw("111.111 *
                    DEGREES(ACOS(LEAST(1.0, COS(RADIANS(lat))
                         * COS(RADIANS(".$lat."))
                         * COS(RADIANS(lng - ". $lng ."))
                         + SIN(RADIANS(lat))
                         * SIN(RADIANS(". $lat ."))))) AS distance_in_km") ;
            $query = $query->select($select);

            $query = $query->having('distance_in_km', '<=', $radius );
        }

        if($ratings = $request->get('raiting_filter')){
        $usersRatings = JobReviews::select('rated_from')->where('rating', $ratings)->get();
        $usersStars = [];   
        foreach($usersRatings as $key => $usersRating){

            $usersStars[]  = $usersRating['rated_from'];
        }
        $query = $query->whereIn('id', $usersStars);
        //echo "<pre>"; print_r($usersStars); echo "</pre>";
       }

        if($order = $request->get('price_order')){
            $query_ = UserPlans::select('user_id', \DB::raw("max(price) as price") )->groupBy('user_id');
            if($order == 'lowheigh'){
                $query_ = $query_->orderBy('price', 'asc');
            }else{
                $query_ = $query_->orderBy('price', 'desc');
            }
                
            if($lowPrice && $lowPrice != ''){
                $query_ = $query_->where('price', '>=', $lowPrice);
            }
            if($highPrice && $highPrice != ''){
                $query_ = $query_->where('price', '<=', $highPrice);
            }

            $userPlans = $query_->get();

            $user_ = [];
            foreach ($userPlans as $key => $userPlan) {
                $user_[] = $userPlan['user_id'];
            }
            $query = $query->whereIn('id', $user_)->orderByRaw("FIELD(id , ".implode(",",$user_).") ASC");
            $query = $query->orderByRaw("FIELD(id , ".implode(",",$user_).") ASC");

        }
        if( $platforms_ = $request->get('platforms') ){

            $platforms_ = array_filter(explode(",", $platforms_ ));
            
            if(count($platforms_)){
                $pla = SocialPlatform::whereIn('slug', $platforms_)->pluck('id')->toArray();

                $userStats  = UserPlatformStats::whereIn('platform_id', $pla)->pluck('user_id')->toArray();
                $query = $query->whereIn('id', $userStats);
            }

        }

        $influencers = $query->paginate(9);
        if($request->ajax()){
            $html = view('influencer.result', compact('influencers'))->render();
            return $html ;
            $array['is_more'] =  $influencers->lastItem() && $influencers->lastItem() < $influencers->total();
            return response()->json($array);
        }

        return view('pages.influencers', compact('page_title', 'page_description', 'influencers', 'categories', 'maxPrice', 'lowPricePer', 'highPricePer','order','get_category', 'radious', 'platforms','race'));
    }



    // KTDatatables
    public function beInfluencer()
    {
        $page_title = 'Be Influencer';
        $page_description = 'This is KTdatatables test page';
        $user = auth()->user() ;
        if(!$user){
            $beInfluencer = true;
            return view('auth.login', compact('page_title', 'page_description', 'beInfluencer'));
        }        

        $categories = Categories::where('status', 1)->pluck('name', 'id')->toArray();

        $platforms = UserPlatformStats::where('user_id', $user['id'])->pluck('platform_id')->toArray();
        $request = InfluencerRequests::where('user_id', $user['id'])->first();

        return view('pages.be_influencer', compact('page_title', 'page_description', 'categories', 'user', 'platforms', 'request'));
    }
    public function platformCat(Request $request){
        $data = $request->all();
         //$data= $request->platId;
        $categories1 = DB::table('social_platform_categories')
        ->join('categories', 'social_platform_categories.category_id', '=', 'categories.id')
        ->where('social_platform_categories.platform_id',$data['platId'])
        ->where('categories.deleted_at',NULL)
        ->select('categories.id','categories.name')
        ->get();

        $cats = [];
        foreach($categories1 as $key => $cat){
            $cats[$cat->id]['id'] = $cat->id;
            $cats[$cat->id]['name'] = $cat->name;
        }

        $categories1 = $cats ;

        $data= view('influencer.platform_category',compact('categories1'))->render();
       
        return $data;
    }

    public function becomeInfluencer($email)
    {   
        $user=User::where('email','=',$email)->first();

        if (!$userToken=JWTAuth::fromUser($user)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        } else if(!auth()->user()) {
            $attempt = Auth::guard('web')->login($user);
        }
        //$user->id
        $page_title = 'Be Influencer';
        $page_description = 'This is KTdatatables test page';
        $user = auth()->user() ;
        if(!$user){
            $beInfluencer = true;
            return view('auth.login', compact('page_title', 'page_description', 'beInfluencer'));
        } 
              

        $categories = Categories::where('status', 1)->pluck('name', 'id')->toArray();

        $platforms = UserPlatformStats::where('user_id', $user['id'])->pluck('platform_id')->toArray();
        $request = InfluencerRequests::where('user_id', $user['id'])->first();

        return view('pages.become_influencer', compact('page_title', 'page_description', 'categories', 'user', 'platforms', 'request'));
    }

    public function verifyDetails(Request $request){

        $user = auth()->user();
        if(empty($user)){
            $request->session()->flash('error','Please login before applying to become a influencer.');
            return redirect('/login');
        }

        $inputs = $request->all();
        // dd($inputs);

        $user->category = @$inputs['category'];
        $user->save();

        switch( $inputs['platform'] ){
            case SocialPlatform::INSTAGRAM ;
                $url = 'verify/instagram' ;
                break ;
            case SocialPlatform::FACEBOOK ;
                $url = 'verify/facebook' ;
                break ;
            case SocialPlatform::YOUTUBE ;
                $url = 'verify/youtube' ;
                break ;
            case SocialPlatform::TIKTOK ;
                $url = 'verify/tiktok' ;
                break ;
            case SocialPlatform::TWITTER ;
                $url = 'verify/twitter' ;
                break ;
        }

        return redirect($url);

    }


    public function storeInfluencer( Request $request )
    {
        $page_title = 'Be Influencer';
        $page_description = 'This is KTdatatables test page';
        $categories = Categories::where('status', 1)->pluck('name', 'id')->toArray();

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'i_username' => 'required',
            'category' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $user = auth()->user() ;
        if($user['type'] == 2){
            $request->session()->flash('error','Your requested is already approved.');
            return redirect()->back()->withInput();
        }        
        $isExists = InfluencerRequests::where('user_id', $user['id'])->first();
        // echo '<pre>';print_r($isExists->status);die;
        if(!empty($isExists) && $isExists->status == 0){
            $request->session()->flash('error','Your request already sent. Please contact to admin.');
            return redirect()->back()->withInput();
        }
        if(!empty($isExists) && $isExists->status == 2){
            $request->session()->flash('error','You requested has been rejected, Please contact admin.');
            return redirect()->back()->withInput();
        }
        $instaDetails = User::getInstaDetails($inputs['i_username']);
        // echo '<pre>';print_r($instaDetails); die;
        $raw = [
            'user_id' => auth()->user()->id,
            'username' => @$inputs['i_username'],
            'followers' => @$instaDetails['followers'],
            'f_username' => @$inputs['f_username'],
            'f_followers' => @$inputs['f_followers'],
            'category' => @$inputs['category'],
            'account_verified' => 0,
            'status' => 0
        ];

        $user_ = User::find($user['id']);

        if(InfluencerRequests::create($raw)){
            $user_->followers = @$instaDetails['followers'];
            $user_->save();

            $app_id = env('CLIENT_ID');
            $redirect_uri = env('REDIRECT_URL');
            $request->session()->flash('alert','Your account request is submitted successfully, you will be notified or a mail will be sent to you once the account is approved');
            // return redirect("https://api.instagram.com/oauth/authorize?client_id=".$app_id."&redirect_uri=".$redirect_uri."&scope=user_profile,user_media&response_type=code");
        }

        return view('pages.be_influencer', compact('page_title', 'page_description', 'categories', 'instaDetails', 'inputs'));
    }    

    // Select2
    public function select2()
    {
        $page_title = 'Select 2';
        $page_description = 'This is Select2 test page';

        return view('pages.select2', compact('page_title', 'page_description'));
    }

    // custom-icons
    public function customIcons()
    {
        $page_title = 'customIcons';
        $page_description = 'This is customIcons test page';

        return view('pages.icons.custom-icons', compact('page_title', 'page_description'));
    }

    // flaticon
    public function flaticon()
    {
        $page_title = 'flaticon';
        $page_description = 'This is flaticon test page';

        return view('pages.icons.flaticon', compact('page_title', 'page_description'));
    }

    // fontawesome
    public function fontawesome()
    {
        $page_title = 'fontawesome';
        $page_description = 'This is fontawesome test page';

        return view('pages.icons.fontawesome', compact('page_title', 'page_description'));
    }

    // lineawesome
    public function lineawesome()
    {
        $page_title = 'lineawesome';
        $page_description = 'This is lineawesome test page';

        return view('pages.icons.lineawesome', compact('page_title', 'page_description'));
    }

    // socicons
    public function socicons()
    {
        $page_title = 'socicons';
        $page_description = 'This is socicons test page';

        return view('pages.icons.socicons', compact('page_title', 'page_description'));
    }

    // svg
    public function svg()
    {
        $page_title = 'svg';
        $page_description = 'This is svg test page';

        return view('pages.icons.svg', compact('page_title', 'page_description'));
    }

    // Quicksearch Result
    public function quickSearch()
    {
        return view('layout.partials.extras._quick_search_result');
    }

    public function howItWorks()
    {
        $page_title = env('APP_NAME').' | How It Works';
        $page_description = 'How It Works';
        $content = ContentPages::select(['how_it_works'])->first();
        return view('pages.how_it_works', compact('page_title', 'page_description','content'));
    }
    public function termsOfService()
    {
        $content = ContentPages::select(['terms_of_service'])->first();
        $page_title = env('APP_NAME').' | Terms Of Service';
        $page_description = 'Terms Of Service';
        return view('pages.terms_of_service', compact('page_title', 'page_description','content'));
    }
    public function privacyPolicy()
    {
        $content = ContentPages::select(['privacy_policy'])->first();
        $page_title = env('APP_NAME').' | Privacy Policy';
        $page_description = 'Privacy Policy';
        return view('pages.privacy_policy', compact('page_title', 'page_description','content'));
    }

    public function influencerProfile($username){
        $user = User::where('username', $username)->orWhere('id',\Helpers::decrypt($username))->with(['countryDetails'])->first();
        $page_title = env('APP_NAME').' | '.$username;
        $page_description = 'Privacy Policy';
        $rating = "";
        if($user->type == 2){
            $rating = Helpers::get_ratings_average($user->id);
        }
        
        if(\Helpers::decrypt(@$username) == $user->id)
        {
          $query = User::withPlans()->where('id', '!=', \Helpers::decrypt($username))->where('status', User::STATUS_ACTIVE)->where('type', User::TYPE_INFLUENCER);
        }else{
          $query = User::withPlans()->where('username', '!=', $username)->where('status', User::STATUS_ACTIVE)->where('type', User::TYPE_INFLUENCER);
        }
        
        $influencers = $query->paginate(3);
        \Session::put('influencer', $user['id']);
        $categories = PlanCategories::where('status', PlanCategories::STATUS_ACTIVE)->with(['plans'=>function($q){
             $q->where('status', Plans::STATUS_ACTIVE);
        }])->get();
        
        $UserPlans = UserPlans::select('plan_id')->where('user_id',$user->id)->get()->toArray();
        if(!empty($UserPlans)){
            foreach($UserPlans AS $UserPlan){
                $user_plans[] = $UserPlan['plan_id'];
            }
        }else{
            $user_plans = array();
        }

        $platforms = UserPlatformStats::where('user_id', $user['id'])->pluck('platform_id')->toArray();
        $WorkHistory=DB::table('jobs_reviews')
                    ->join('users','users.id','=','jobs_reviews.rated_from')
                     ->where(['rated_by' => $user->id])
                    ->select('jobs_reviews.rated_from','jobs_reviews.rating','jobs_reviews.review','jobs_reviews.created_at','users.first_name','users.last_name','users.image')
                    ->get();
        return view('influencer.profile', compact('page_title', 'page_description','user', 'influencers','categories','user_plans','rating', 'platforms','WorkHistory'));

    }
    
    public function profileSettings(){
        
        $page_title = env('APP_NAME').' | Profile';
        $page_description = 'Profile Settings';
        $user = auth()->user();
        $countries = Countries::all();
        $categories = Categories::where('status', 1)->get()->toArray();
        return view('influencer.manage_profile', compact('page_title', 'page_description','user', 'countries', 'categories'));

    }

    public function profileStore(Request $request){
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            "first_name" => 'required',
            "last_name" => 'required',
            "date_of_bith" => 'required',
            "country_code" => 'required',
            "phone" => 'required',
            "address" => 'required',
            "category"=> 'required_if:type,==,2',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }


        $user = auth()->user();
        if ($request->myfile) {
            $image = $request->file('myfile');
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path("/media/users");
            $image->move($destinationPath , $imagename);         
            $user->image = $imagename;
        }else if($request->get('profile_avatar_remove')){
            $user->image = null;
        }

        $user->first_name = @$inputs['first_name'];
        $user->last_name = @$inputs['last_name'];
        $user->date_of_bith = date('Y-m-d', strtotime(@$inputs['date_of_bith']));
        $user->country_code = @$inputs['country_code'] ;
        $user->phone = @$inputs['phone'];
        $user->city = @$inputs['city'];
        $user->state = @$inputs['state'];
        $user->country = @$inputs['country'];
        $user->address = @$inputs['address'];
        $user->school = @$inputs['school'];
        $user->description = @$inputs['description'];
        $user->category = @$inputs['category'];
        $array['facebook'] = @$inputs['facebook'];
        $array['twitter'] = @$inputs['twitter'];
        $array['pinterest'] = @$inputs['pinterest'];
        $user->social_links = json_encode($array);
        $user->save();

        $request->session()->flash('alert','Your profile is updated successfully.');
        return redirect()->back();
    }

    public function faq()
    {
        $faqs = FaqCat::where('status',1)->get();
        // echo '<pre>';print_r($faqs); die;
        $page_title = env('APP_NAME').' | Faq';
        $page_description = 'Faq';
        return view('pages.faq', compact('page_title', 'page_description','faqs'));
    }

    public function userAgreement()
    {
        $content = ContentPages::select(['user_agreement'])->first();
        $page_title = env('APP_NAME').' | User Agreement';
        $page_description = 'User Agreement';
        return view('pages.user_agreement', compact('page_title', 'page_description','content'));
    }
    public function contactUs(Request $request)
    {
        if($request->post('email')){
            $request->session()->flash('alert', 'You have contacted successfully!');
            $ContactUs = new ContactUs();
            $ContactUs->name = $request->post('full_name');
            $ContactUs->email = $request->post('email');
            $ContactUs->description = $request->post('desc');
            $ContactUs->save();
        }  
        $page_title = env('APP_NAME').' | Contact Us';
        $page_description = 'Contact Us';
        return view('pages.contact_us', compact('page_title', 'page_description'));
    }

}
