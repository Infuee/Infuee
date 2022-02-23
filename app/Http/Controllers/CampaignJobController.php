<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;
use Helpers;
use File;
use ZipArchive;
use Session;
use Redirect;
use App\Models\Campaign;
use App\Models\SocialPlatform;
use App\Models\Categories;
use App\Models\Job;
use App\Models\Transactions;
use App\Models\SocialPlatformCategory;
use App\Models\JobPlatform;
use App\Models\Plans;
use App\User ;
use App\Models\JobProposals ;
use App\Models\UserPostUsrl ;
use App\Models\JobReviews;
use App\Models\Testimonial;
use App\Models\JobHiring;
use App\Models\Messages;
use App\Models\UserWalletTransaction;
use App\Models\Notification;
use App\Models\MessageReadMark;
use App\Models\UserPlatformStats;
use App\Models\Race;
use Carbon\Carbon;
use Pusher\Pusher;
use ReflectionClass;
use PDF;
use App\Mail\TransactionEmailSend;
use App\Jobs\SendEmailTransaction;
use Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Setting;
use App\Models\UserWallet;

class CampaignJobController extends Controller
{

    

    public function campaigns(){
        $campaigns = Campaign::with(['jobs'])->whereStatus(Campaign::STATUS_ACTIVE)->paginate(20);

        return view('influencer.campaign.campaigns', compact('campaigns'));

    }

    public function campaignJobs($slug){
        $user = auth()->id();

        $campaign = Campaign::with(['jobs'])->whereSlug($slug)->whereStatus(Campaign::STATUS_ACTIVE)->first();
        if($user !== $campaign['created_by']){
            $campaign['jobs'] = Job::where('campaign_id', $campaign['id'])->where('status','1')->get();
        }
        
        return view('influencer.campaign.campaign_details', compact('campaign'));
    }

    


    public function createCampaign($slug = false)
    {
        $user = auth()->user();
        
        $page_title = "Create Campaign";
        $page_description = "Create Campaign";
        $campaign = false;
        if($slug){
            $campaign = Campaign::whereSlug($slug)->first();
            if(empty($campaign)){
                return redirect()->back()->with(['error' => "This campaign does not exists anymore. Please try adding a new one."]);
            }
        }
        return view('influencer.campaign.form', compact('page_title', 'page_description', 'user', 'campaign'));

    } 

    public function saveCampaign( Request $request ){
        $inputs = $request->all();
        $id = $request->get('id');
        $user = auth()->user();
        $validator = Validator::make($inputs, [
            'title' => 'required',
            "description"=> "required",
            "location"=> "required",
            "lat"=> "required",
            "lng"=> "required",
            "image" => "nullable|max:2048"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if($id){
            $campaign = Campaign::find($id);
        }else{
            $campaign = new Campaign();
        }
        $campaign->title = $inputs['title'];
        $campaign->slug = $campaign->getSlugs($inputs['title']);
        $campaign->status = @$inputs['status'] ? : Campaign::STATUS_ACTIVE ;
        $campaign->description = $inputs['description'];
        $campaign->location = $inputs['location'];
        $campaign->lat = $inputs['lat'];
        $campaign->lng = $inputs['lng'];
        $campaign->website = $inputs['website'];
        $campaign->created_by = $user->id ;

        $uploadedfile = $request->file('image');

        if ($request->has('image') && !empty($request->file('image'))) {
            $directory = 'uploads/compaign';
            if (! File::exists(public_path().'/'.$directory.'/')) {
                File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
            }
            $filename = preg_replace('/\..+$/', '', $uploadedfile->getClientOriginalName()).time().'.'.$uploadedfile->getClientOriginalExtension();
            $destinationPath = public_path($directory);
            $uploadedfile->move($destinationPath, $filename);
            $campaign->image = $filename ;
        }
        
        if($campaign->save()){
            if( Helpers::isWebview() ){
               return redirect(url('success'));  
            }
            $request->session()->flash('success', 'Campaign '. ($id ? 'updated' : 'added') .' successfully.');
        }
        return redirect()->intended('campaigns');
    }

    public function jobs(Request $request){

        $query = Job::whereNull('campaign_id')->where(function($q){
            $q->where('created_to', null)->orWhere('created_to', auth()->id());
        })->whereStatus(Job::STATUS_ACTIVE);

        $categories = Categories::where('status', 1)->pluck('name', 'id')->toArray();
        $race = Race::where('status', 1)->pluck('title', 'id')->toArray();
        $platforms = SocialPlatform::where('status', SocialPlatform::STATUS_ACTIVE)->paginate(10, ['*'], 'platforms');

        $lowPrice = $request->get('price_min')?:0;
        $maxPrice = Job::orderBy('price', 'DESC')->pluck('price')->first();
        $maxPrice = $maxPrice?:0;
        $highPrice = $request->get('price_max')?:$maxPrice;
        $maxPriceData = ($maxPrice== null || $maxPrice== '0') ? 1 : $maxPrice;
        $filterquery = $query->where('price', '>=' ,$lowPrice)->where('price', '<=', $highPrice);

        $radious = 100 ;
        if($get_category = $request->get('categories')){
            $get_category = array_filter(explode(",", $get_category ));
            
            if(count($get_category)){
                $query = $query->whereIn('category_id', $get_category);
            }
        }

        if($get_race= $request->get('race')){
            $get_race = array_filter(explode(",", $get_race ));
            $query = $query->where(function($q) use ($get_race){
                if(count($get_race)){
                    foreach($get_race as $race){
                        $q->orWhere('race_id', 'LIKE', "%\"{$race}\"%");
                    }
                }
            });
        }

        $select = ['*'];
        if($age = $request->get('age')){
            $range = Job::ageFilter($age);
            $array = explode( ' - ' , $range);
            $min = @$array[0];
            $max = @$array[1];
            if (! (is_null($min) && is_null($max))) {
              $query = $query->where('min_age', '>=', $min)
                     ->where('max_age', '<=', $max);
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
        }

        if($order = $request->get('price_order')){
            if($order == 'lowheigh'){
                $query = $query->orderBy('price', 'asc');
            }else{
                $query = $query->orderBy('price', 'desc');
            }
        }else{
            $query = $query->orderBy('price', 'asc');
        }
        if( $platforms = $request->get('platforms') ){
            $platforms_ = array_filter(explode(",", $platforms ));
            if(count($platforms_)){
                $pla = SocialPlatform::whereIn('slug', $platforms_)->pluck('id')->toArray();
                $userStats  = JobPlatform::whereIn('platform_id', $pla)->pluck('job_id')->toArray();
                 $query = $query->whereIn('id', $userStats);
            }
        }

        $jobs = $query->paginate(20,['*'], 'page');
        $page = $request->get('page');
        if($request->ajax()){
            $html = view('influencer.job.jobs_result', compact('jobs', 'page'))->render();
            return $html ;
            $array['is_more'] =  $jobs->lastItem() && $jobs->lastItem() < $jobs->total();
            return response()->json($array);
        }
        return view('influencer.job.jobsList', compact('jobs','categories','race','maxPrice', 'filterquery','order','get_category', 'radious', 'platforms','race', 'page'));

    }

    public function createJob($slug = false, $jobSlug = false){

        $user = auth()->user();
        $campaign = false;
        
        if($slug && ( ($jobSlug && \Request::segment(3) == 'edit') || ( !$jobSlug && \Request::segment(3) !== 'edit' ) ) ){
            $campaign = Campaign::whereSlug($slug)->first();
            if(empty($campaign)){
                return redirect()->back()->with(['error' => "This campaign does not exists anymore. Please try adding a new one."]);
            }
            $page_title = "Create Job under ".$campaign['title'];
            $page_description = "Create Campaign under ".$campaign['title'];
        }else{
            $jobSlug = $slug ;
            $page_title = "Hire Influencer";
            $page_description = "Hire Influencer";
        }
        $platforms = SocialPlatform::where('status','1')->get();
        $categories = Categories::where('status','1')->get();
        $race = Race::where('status','1')->get();
        $job = false ;
        $minimum_followers ='0';
        $race_id ='0';
        if($jobSlug){
            
             $job = Job::whereSlug($jobSlug)->first();
             $minimum_followers =json_decode($job->minimum_followers,true);
             $race_id =json_decode($job->race_id,true);
            if(empty($job)){
                return redirect()->back()->with(['error' => "This job does not exists anymore. Please try adding a new one."]);
            }
        }   
        $hirejob=JobHiring::where(['job_id' => @$job->id, 'user_id' => auth()->id() ])->first();
        return view('influencer.job.form', compact('page_title', 'page_description', 'user', 'campaign', 'platforms', 'categories', 'job','minimum_followers','race','race_id','hirejob'));
    }

    public function hireInfluencer($username = false){
        
        $page_title = "Hire Influencer";
        $page_description = "Hire Influencer";

        $user = auth()->user();
        
        $influencer = User::where('id', Helpers::decrypt($username))->first();

        $campaign = false;
        
        $platforms = SocialPlatform::where('status','1')->get();
        $categories = Categories::where('status','1')->get();
        $race = Race::where('status','1')->get();
        $job = false ;
        
        return view('influencer.job.form', compact('page_title', 'page_description', 'user', 'campaign', 'platforms', 'categories', 'job', 'influencer','race'));
    }


    public function saveJob(Request $request, $slug = false){
        
        $campaign = false ;
        if($slug){
            $campaign = Campaign::whereSlug($slug)->first();
            if(empty($campaign)){
                return redirect()->back()->with(['error' => "This campaign does not exists anymore. Please try adding a new one."]);
            }
        }
        $inputs = $request->all();
        $id = $request->get('id');
        $min_age = floatval(str_replace(',' ,'', $request->input('min_age')));
        $max_age = floatval(str_replace(',' ,'', $request->input('max_age')));
        //Get your range
        $min = $min_age  + 0.01;
        $max = $max_age - 0.01;
        $validator = Validator::make($inputs, [
            'title' => 'required',
            "description"=> "required",
            "caption"=> "required",
            "image"=> "nullable|max:2048",
            "image_video" => "nullable|max:2048",
            "platforms" => "required|array|min:1",
            // "category" =>  "required",
            // "influencers" => "required",
            "minutes" => "required_if:seconds,==,NULL",
            "seconds" => "required_if:minutes,==,NULL",
            // "promo_days" => "required",
            //"price" => "required"
             'min_age' => [
            'nullable',
            function($attribute, $value, $fail) use($min_age, $max) {
                    if ($min_age < 0 ||  $min_age > $max) {
                        return $fail(' Min age must be between 0 and max age');
                    }
                }],
            'max_age' => [
            'nullable',
            function($attribute, $value, $fail) use($max_age, $min) {
                    if ($max_age < $min) {
                        return $fail(' Max age must be greater than min age.');
                    }
                }] 
            

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        if($id){
            $job = Job::find($id);
        }else{
            $job = new Job();
        }
        $user = auth()->user();
        $influencer = User::find(@$inputs['influencer']);

        $job->campaign_id = @$campaign['id'] ? : $job->campaign_id ;
        $job->title = $inputs['title'];
        $job->slug = $job->getSlugs($inputs['title']);
        $job->status = @$inputs['status'] ? : Job::STATUS_ACTIVE ;
        $job->description = @$inputs['description'];
        $job->duration = ((int) $inputs['minutes'] * 60 )  + (int) $inputs['seconds'];
        $job->category_id = @$inputs['category']?: (@$influencer['category'] ?: 1 );
        $job->race_id = json_encode(@$inputs['race_id']);
        $job->influencers = @$inputs['influencers']?:1;
        $job->promo_days = @$inputs['promo_days']?:0;
        $job->price = $inputs['price'];
        $job->created_by = @$user['id'];
        $job->created_to = @$inputs['influencer'];
        $job->location = @$inputs['location'];
        $job->min_age = @$inputs['min_age'];
        $job->max_age = @$inputs['max_age'];
        $job->lat = @$inputs['lat'];
        $job->lng = @$inputs['lng'];
        $job->caption = @$inputs['caption'];
        $job->radius = @$inputs['radius']? :"";
        $job->minimum_followers = json_encode(@$inputs['minimum_followers']);
        $uploadedfile = $request->file('image');
        $uploadedvideoimagefile = $request->file('image_video');

        if ($request->has('image') && !empty($request->file('image'))) {
            $directory = 'uploads/job';
            if (! File::exists(public_path().'/'.$directory.'/')) {
                File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
            }
            $filename = preg_replace('/\..+$/', '', $uploadedfile->getClientOriginalName()).time().'.'.$uploadedfile->getClientOriginalExtension();
            $destinationPath = public_path($directory);
            $uploadedfile->move($destinationPath, $filename);
            $job->image = $filename ;
        }
        if ($request->has('image_video') && !empty($request->file('image_video'))) {
            $directory = 'uploads/job';
            if (! File::exists(public_path().'/'.$directory.'/')) {
                File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
            }
            $filename = preg_replace('/\..+$/', '', $uploadedvideoimagefile->getClientOriginalName()).time().'.'.$uploadedvideoimagefile->getClientOriginalExtension();
            $destinationPath = public_path($directory);
            $uploadedvideoimagefile->move($destinationPath, $filename);
            $job->image_video = $filename ;
        }
        
        if($job->save()){
            JobPlatform::where('job_id', $job->id)->delete();
            foreach($inputs['platforms'] as $platform){
                JobPlatform::create(['platform_id'=>$platform, 'job_id' => $job->id]);
            }
            $request->session()->flash('success', 'Job '. ($id ? 'updated' : 'added') .' successfully.');
        }
        
        if( Helpers::isWebview() ){
            
          return redirect(url('success'));  
        } 
        if($campaign){
            return redirect()->intended('campaign/'. $campaign['slug'] );
        }

        if( !$campaign && $id ){
            return redirect()->intended('job/'. $job['slug'] );   
        }
        $completedjobs = Job::whereNull('campaign_id')->where('status', 4)->latest()->where('created_by', auth()->id())->paginate(20);
        $jobs = Job::whereNull('campaign_id')->whereStatus(Job::STATUS_ACTIVE)->where('status', 1)->latest()->where('created_by', auth()->id())->paginate(20);
        return redirect()->intended('my-jobs');
        //return view('influencer.job.jobs', compact('jobs','completedjobs'));


    }


    public function jobDetails($slug){

        $job = Job::whereSlug($slug)->first();
        $race =json_decode(@$job->race_id,true);

        $race_id=Race::whereIn('id',is_array($race)? $race :[$race])->get();
        $minimum_followers =json_decode(@$job->minimum_followers,true);
        $CatId=@$job['category_id'];
        $jobs = Job::whereNull('campaign_id')
        ->whereStatus(Job::STATUS_ACTIVE)
        ->where('category_id',$CatId)
        ->whereNotIn('id',[@$job->id])
        ->latest()
        ->paginate(20);

        if(empty($job)){
            return redirect()->back()->with(['error' => "This job does not exists. Please try selecing another one."]);
        }
        
        $user = auth()->user();
        $page_title = "Job Details :- ".$job['title'];
        $page_description = "Job Details :- ".$job['title'];
        return view('influencer.job.view', compact('page_title', 'page_description', 'user', 'job','jobs','race_id'));
    }

    public function status($slug){

        $job = Job::whereSlug($slug)->first();
        if(empty($job)){
            return response()->json(['status'=>false, 'message' => "This job does not exists."]);
        }
        $job->status = $job->status == Job::STATUS_ACTIVE  ? Job::STATUS_DEACTIVATED : Job::STATUS_ACTIVE ;
        $job->save();
        return response()->json(['status'=>true, 'message' => "This job is ". ( $job->status == Job::STATUS_DEACTIVATED ? 'drafted' : 'published' ) . ' successfully']);
    }

    public function delete($slug){

        $job = Job::whereSlug($slug)->first();
        if(empty($job)){
            return response()->json(['status'=>false, 'message' => "This job does not exists."]);
        }
        $job->delete();
        return response()->json(['status'=>true, 'message' => "This job is deleted successfully" ]);
    }

    

    public function influencersCategories($category){
        $category = SocialPlatform::whereSlug($category)->first();
        $platform_catogery= DB::table('social_platform_categories')
           ->join('categories','categories.id','=','social_platform_categories.category_id')
            ->where(['platform_id' => $category->id])
            ->select('social_platform_categories.platform_id','categories.*')
            ->get();

        return view('influencer.category_influencers', compact('category','platform_catogery'));        


    }
     public function influencersPlatformCategories($category,$platform_cat,$id){
        $platformName = SocialPlatform::whereSlug($category)->first();
        $platform_catogery= DB::table('users')
            ->join('user_plans','user_plans.user_id','=','users.id')
            ->join('user_platform_stats','user_platform_stats.user_id','=','users.id')
            ->where('user_platform_stats.platform_id',$platformName->id)
            ->where('users.category',$id)
            ->where('users.type','2')
            ->where('users.account_verified','1')
            ->groupBy('user_plans.user_id')
            ->select('users.*')
            ->paginate(10);
        $categoryName = Categories::whereId($id)->first();
        return view('influencer.influencers_platform_category', compact('platformName','platform_catogery','categoryName','id'));
     }
     
     public function search(Request $request){
        $data = $request->all();
        $keywords= $request->keywords;
        
        $platform_catogery = DB::table('user_platform_stats')
            ->join('users','users.id','=','user_platform_stats.user_id')
            ->join('jobs_reviews','jobs_reviews.rated_by','=','user_platform_stats.user_id')
            ->join('user_plans','user_plans.user_id','=','user_platform_stats.user_id')
            ->where('users.type', '2')
            ->where('users.account_verified', '1')
            ->orderBy('jobs_reviews.rating','DESC')
            ->groupBy('users.id')
            ->select('users.*','jobs_reviews.rating')
            ->where(function($q) use ($keywords){
                $q->where('users.first_name', 'LIKE', "%{$keywords}%")->orWhere('users.last_name', 'LIKE', "%{$keywords}%");
            })
            ->where(['platform_id' => $data['catId'] ])
            ->paginate(10);

        return view('influencer.platform_category_search',compact('platform_catogery'))->render();
    }

    public function applyJob($slug){

        $job = Job::whereSlug($slug)->first();
        if(empty($job)){
            return response()->json(['status'=>false, 'message' => "This job does not exists."]);
        }

        $proposal = JobProposals::where(['job_id' => $job->id, 'influencer_id' => auth()->id() ])->first();
        $postUrl=JobPlatform::where('job_id',$job->id)->get();
        $hirejob=JobHiring::where(['job_id' => $job->id, 'influencers_id' => auth()->id() ])->first();
        $reviews=JobReviews::where(['order_id' => $job->id, 'rated_by' => auth()->id() ])->first();
        $page_title = "Job proposal :- ".$job['title'];
        $page_description = "Job proposal :- ".$job['title'];
        return view('influencer.job.proposal', compact('job', 'proposal','postUrl','hirejob','reviews'));
    }

    public function saveAttachments(Request $request){
        $uploadedfile = $request->file('file');
        if ($request->has('file') && !empty($request->file('file'))) {
            $directory = 'uploads/jobs/attachments';
            if (! File::exists(public_path().'/'.$directory.'/')) {
                File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
            }
            $filename = preg_replace('/\..+$/', '', $uploadedfile->getClientOriginalName()).time().'.'.$uploadedfile->getClientOriginalExtension();
            $destinationPath = public_path($directory);
            $uploadedfile->move($destinationPath, $filename);
            
            $size = (!File::exists( $destinationPath. '/' . $filename)) ? 0 : File::size($destinationPath .'/' . $filename);
            $url = url($directory .'/' . $filename);
            $imageAnswer = [
                'original' => $filename,
                'size' => $size,
                'url' => $url,
            ];
            return response()->json(['success' => $imageAnswer]);
        }
    }

    public function submitProposal( Request $request ){
        $inputs = $request->all();
        $rules = [
                'cost' => 'required',
                'cover_latter' => 'required',];

            $customMessages = [
                'cover_latter.required' => 'The cover letter field is required.',];

            $this->validate($request, $rules, $customMessages);

        // if ($validator->fails()) {
        //     return redirect()->back()->withInput()->withErrors($validator);
        // }
     
        
        $job = Job::find($inputs['id']);
        if(empty($job)){
            $request->session()->flash('error', 'Job not found');
            return redirect()->intended('job/'. $job['slug'] );
        }
        $user = auth()->user() ;
        $proposal = JobProposals::where(['job_id' => $job['id'], 'influencer_id' => $user['id']])->first();
        if(empty($proposal)){
            $proposal = new JobProposals();
            $proposal->influencer_id = $user['id'];
            $proposal->job_id = $inputs['id'];
        }


        $className = get_class($proposal);
        $reflection = new ReflectionClass($className);
        $modelClassName = $reflection->getShortName();


        $options1 = array(
                'cluster' => 'ap2',
                'useTLS' => true
            );
        $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options1
            );
        

        $dataNotification1 = array('user_id' => $job->created_by, 'notification' => $user->first_name . ' ' .$user->last_name ." Influencer Apply on Your " .$job->title . " Job.",'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),'model_class' =>$modelClassName,'model_id' =>$inputs['id'],'seen'=>0);
        Notification::insert($dataNotification1);
        $dataNotification = [ 'count' => Notification::where('user_id', $job->created_by)->where('seen', '0')->count(), 'user_id' => $job->created_by, 'notification' => $user->first_name . $user->last_name ." Influencer Apply on Your " .$job->title];
        

        $pusher->trigger('my-channel', 'my-event', $dataNotification);

        
        if(empty($inputs['proposal_attachments'])){
            $inputs['proposal_attachments']='';
        }
        if(empty($inputs['cover_latter'])){
            $inputs['cover_latter']='';
        }
        $proposal->cost = $inputs['cost'];
        $proposal->cover_latter = $inputs['cover_latter'];
        $proposal->attachments = $inputs['proposal_attachments'];
        $proposal->job_created_by = $job['created_by'];
        $proposal->save();
        
        $request->session()->flash('success', 'Proposal submitted successfully.');
        return redirect()->intended('job/'. $job['slug'] );

    }

    public function proposals( $slug ){
        $job = Job::whereSlug($slug)->first();
        $proposalCount = JobProposals::where('job_id', $job['id'])
        ->where('influencers_hire_status', '1')
        ->count('influencer_id');
        
        if(empty($job)){
            $request->session()->flash('error', 'You are not authorised to access this page.');
            return redirect()->intended('job/'. $job['slug'] );
        }
        $user = auth()->user();
        if($job->created_by !== $user['id']){
            $request->session()->flash('error', 'You are not authorised to access this page.');
            return redirect()->intended('job/'. $job['slug'] );
        }


        $proposals = UserPostUsrl::with('user')->where('job_id', $job['id'])->groupBy('influencer_id')->paginate(20);
        return view('influencer.job.proposals', compact('job', 'proposals', 'proposalCount'));

    }

    public function proposal($slug, $id){

        $id = Helpers::decrypt($id);
        $job = Job::whereSlug($slug)->first();
        $reviews=JobReviews::where(['order_id' => $job->id, 'rated_by' => auth()->id() ])->first();
        if(empty($job)){
            $request->session()->flash('error', 'You are not authorised to access this page.');
            return redirect()->intended('job/'. $job['slug'] );
        }
        
        if(!$id){
            $request->session()->flash('error', 'You are not authorised to access this page.');
            return redirect()->intended('job/'. $job['slug'] . '/proposals');
        }

        $proposal = UserPostUsrl::with('user')->where('id', $id)->first();
        $posturl = UserPostUsrl::where('influencer_id', $proposal->influencer_id)->where('job_id',$job->id)->get();

        $jobs1 = Job::where('status', 2)->latest()->where('created_by', auth()->id())->first();
       $hirejob=JobHiring::where(['job_id' => @$job->id, 'user_id' => auth()->id() ])->first();
        $wallet = \App\Models\UserWallet::where('user_id',  auth()->id())->first();
        return view('influencer.job.proposal_details', compact('job', 'proposal','jobs1','posturl','reviews','wallet'));        

    }


    public function myJobs(){

        $user = auth()->user();

        $userJobIds = Job::where('created_by', $user->id)->pluck('id')->toArray();

        $activeJobsIds = UserPostUsrl::where(function($q) use ($userJobIds, $user){
            $q->whereIn('job_id', $userJobIds)->orWhere('influencer_id', $user->id);
        })->where('status', 0)->pluck('job_id')->toArray();

        $completedJobsIds = UserPostUsrl::where(function($q) use ($userJobIds, $user){
            $q->whereIn('job_id', $userJobIds)->orWhere('influencer_id', $user->id);
        })->where('status', 2)->pluck('job_id')->toArray();
        
        $jobs = Job::whereNull('campaign_id')->whereNotIn('id', $completedJobsIds )->whereNotIn('id', $activeJobsIds )->whereStatus(Job::STATUS_ACTIVE)->latest()->where('created_by', auth()->id())->paginate(20, ['*'], 'jobs');
        $completedjobs = Job::whereIn('id', $completedJobsIds)->paginate(20, ['*'], 'completedjobs');
        $activeJobs = Job::whereIn('id', $activeJobsIds)->paginate(20, ['*'], 'activeJobs') ;

        return view('influencer.job.jobs', compact('jobs','completedjobs', 'activeJobs'));
    }

    public function myActiveJobs(){
        $user = auth()->user();

        $userJobIds = Job::where('created_by', $user->id)->pluck('id')->toArray();

        $activeJobsIds = UserPostUsrl::where(function($q) use ($userJobIds, $user){
            $q->whereIn('job_id', $userJobIds)->orWhere('influencer_id', $user->id);
        })->where('status', 0)->pluck('job_id')->toArray();
        
        $jobs = Job::whereIn('id', $activeJobsIds)->paginate(20) ;
        
        return view('influencer.job.myactivejobs', compact('jobs'));

    }


    public function myCampaigns(){
        $campaigns = Campaign::with(['jobs'])->whereStatus(Campaign::STATUS_ACTIVE)->where('created_by', auth()->id())->paginate(20);

        return view('influencer.campaign.campaigns', compact('campaigns'));

    }

    public function hirejobs( Request $request ){
        $data = $request->all();

        $userId =  auth()->user()->id;
        $user = User::where('id', $userId)->first();
        $userType = auth()->user()->type;
        $userTo = Job::where('id', $data['jobId'])->first();
        $job = $userTo;

        if(JobProposals::where(['job_id' => $job['id'], 'influencers_hire_status'=> 1])->count() >= $job->influencers ){
            return response()->json(['success' => false, 'message' => "You have exceeded with maximum allowed influencers on this job."]);
        }
    
        $jobUrl = url('/').'/job/'.(@$data['joburl'] ? : $job['slug'] );
        
        $proposal = JobProposals::where(['job_id' => $job['id'], 'influencer_id' => $data['jobhireinfluencers']])->first();

        $influncerUser = User::find($data['jobhireinfluencers']);
        $email = $influncerUser->email;
        $emailUser = $user->email;
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        $settings = Setting::first();
        $totalComm = ($settings->commission / 100) * $proposal['cost'];
        $finalAmountJob = $proposal['cost'] - $totalComm;


        $influencerswallet = \App\Models\UserWallet::where('user_id', $data['jobhireinfluencers'])->first();
        $adminswalletID = \App\Models\UserWallet::where('admin_id', '1')->first();
        
        if(empty($influencerswallet)){
            $createWalletAcc = array(
                'user_id' => $data['jobhireinfluencers'],
            );
            $influencerswallet = \App\Models\UserWallet::create($createWalletAcc);
        }

        if( empty($adminswalletID) ){
            $createWalletAcc = array(
                'admin_id' => '1',
            );
            $adminswalletID = \App\Models\UserWallet::create($createWalletAcc);
        }

        $wallatTransaction1 = array(
           'wallet_id' => $adminswalletID['id'], 
           'commission' => $totalComm, 
           'amount' => $proposal['cost'], 
           'influencer_id' => $data['jobhireinfluencers'], 
           'job_id' => $data['jobId'], 
           'transaction_type' => '2', 
           'description' => 'Commission Recieved From ' . auth()->user()->first_name . ' ' . auth()->user()->last_name, 
           'transaction_id' => "JC_" .Str::random(20), 
           'created_by' => $userId, 
        );
   
        UserWalletTransaction::insertGetId($wallatTransaction1);

        $adminswalletID->amount = $totalComm + $adminswalletID->amount;;
        $adminswalletID->save();

        $influencerswallet->amount = $influencerswallet['amount'] + $finalAmountJob;
        $influencerswallet->save();
        $influencersWalletAmount = $influencerswallet->amount ; 
        $pdf1 = PDF::loadView('influencer.job.contract_pdf_influencer', compact(['data','influncerUser','influencersWalletAmount','userTo', 'proposal']));
        $content = $pdf1->download()->getOriginalContent();
        $filename = uniqid('pdf_') . '.pdf';
        Storage::disk('pdf')->put($filename,$content);

        $details = array(
            'email'     => $email,
        );
        $users = array(
            'user_name_influ' => $influncerUser['first_name'],
            'job_cost'     => $finalAmountJob,
            'user_name'    => $user['first_name'],
            'job_name'    => $userTo->title,
            'pdf_file' =>$filename,
            "type" => 'Credit',
        );
        dispatch(new SendEmailTransaction($details,$users));
        
        // Start RealTime Notification
        $dataNotification1 = [
            'user_id' => $data['jobhireinfluencers'], 
            'notification' => "You hired by ". auth()->user()->first_name . ' on the job <a href="'. url('job/'. $job['slug'] ) .'"'. $job['title'].'</a>',
            'model_class' => get_class($userTo),
            'model_id' => $data['jobId'],
            'seen'=>0
        ];
        Notification::insert($dataNotification1);
        /*End of real time notification*/


        $options = array(
                'cluster' => 'ap2',
                'useTLS' => true
            );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        
        $dataNotification = [ 
            'count' => Notification::where('user_id', $data['jobhireinfluencers'])->where('seen', '0')->count(), 
            'user_id' => $userId, 
            'notification' => "You hired by ". auth()->user()->first_name . ' on the job <a href="'. url('job/'. $job['slug'] ) .'"'. $job['title'] .'</a>'
        ] ;
        $pusher->trigger('my-channel', 'my-event', $dataNotification);
        
        $checkUser = \App\Models\ChatRoom::where('job_id',$data['jobId'])->count();

        if( $checkUser == '0' ){
            $dataChatRoom = array(
                'job_id' => $data['jobId'],
            );
            $dataChat = \App\Models\ChatRoom::insert($dataChatRoom);
            
        } 
        $newChatRoomId = \App\Models\ChatRoom::where('job_id', $data['jobId'])->first();
        $chatRoomPartcipeant = \App\Models\ChatRoomPartcipants::where('user_id', $userId)
        ->where('job_id',$data['jobId'])
        ->count();

        if($chatRoomPartcipeant == '0'){
            $finalUserPartcipenat = array(
                'user_id' => $userId,
                'chat_room_id' => $newChatRoomId->id,
                'job_id' => $data['jobId'],
                'job_status' => "Hired By",
                'created_at' => $todayDate,
                'updated_at' => $todayDate,
            );
            $finalInfluncerPartcipenat = array(
                'user_id' => $data['jobhireinfluencers'],
                'chat_room_id' => $newChatRoomId['id'],
                'job_id' => $data['jobId'],
                'job_status' => "Hired To",
                'created_at' => $todayDate,
                'updated_at' => $todayDate,
            );
            $finalChatPartcipent = \App\Models\ChatRoomPartcipants::insert([$finalUserPartcipenat,$finalInfluncerPartcipenat]);

            //entry in message table
            $messpartcipentId = \App\Models\ChatRoomPartcipants::where('job_status', "Hired By")->first();
            
            $messUserData = array(
                'participant_id' => $messpartcipentId['id'],
                'chat_room_id' => $newChatRoomId->id,
                'reply_id' => '0',
                'message' => "You have hired for " .  $userTo->title  . " this job <a target='_blank'  href=". $jobUrl . "> " . $userTo->title . "</a>" ,
                'job_id' => $data['jobId'],
                'job_offer_status' => "Approved",
                'created_at' => $todayDate,
                'updated_at' => $todayDate,
            );
           
            $messageId = Messages::insertGetId($messUserData);

            $mesUnread = array(
                'message_id' => $messageId,
                'participant_id' => $messpartcipentId['id'],
                'chat_room_id' => $newChatRoomId->id,
                'seen_status' => '0',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );
            MessageReadMark::insert($mesUnread);

        }
        // end message

        
        $user = User::where('id', $userId)->first();

        $email = $user->email;
        $jobHire =  array(
            'job_id' => $data['jobId'], 
            'user_id' => $userTo->created_by, 
            'influencers_id' => $data['jobhireinfluencers'], 
            'job_hire_status' => JobHiring::STATUS_ACTIVE, 
            'created_at' => $todayDate, 
            'updated_at' => $todayDate,
            'price' => @$proposal['cost']
        );
        $wallet = \App\Models\UserWallet::where('user_id', $userId)->first();
        $totalAmount = $wallet['amount'] - $proposal['cost'];
        $finalAmount = \App\Models\UserWallet::where('user_id', $userId)->update(['amount' => $totalAmount]);
        // mail send to user for amount less
        
        $pdf = PDF::loadView('influencer.job.contract_pdf_u', compact(['data','totalAmount','userTo', 'influncerUser', 'proposal']));
        $content1 = $pdf->download()->getOriginalContent();
        $filenamepdf = uniqid('pdf_') . '.pdf';
        Storage::disk('pdf')->put($filenamepdf,$content1);
        

        $details1 = array(
            'email'     => $emailUser,
        );
        $usersDetails = array(
            'user_name_influ' => $user['first_name'],
            'job_cost'     => $proposal['cost'],
            'user_name'    => $influncerUser['first_name'],
            'job_name'    => $userTo->title,
            'pdf_file' =>$filename,
            "type" => 'Debit',
        );
       
        dispatch(new SendEmailTransaction($details1,$usersDetails));
       
        
        $className1 = get_class($wallet);
        $reflection1 = new ReflectionClass($className1);
        $modelClassName1 = $reflection1->getShortName();

        
        $dataNotification1 = array('user_id' => $data['jobhireinfluencers'], 'notification' => "$". $proposal['cost'] ." amount credited to your wallet for ". $userTo->title ." job",'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),'model_class' =>$modelClassName1,'model_id' =>$userId,'seen'=>0);
        Notification::insert($dataNotification1);
        $dataNotification11 = [ 'count' => Notification::where('user_id', $data['jobhireinfluencers'])->where('seen', '0')->count(), 'user_id' => $userId, 'notification' =>"$" . $proposal['cost'] .  " Amount Credit for this ". $userTo->title];
        

        $pusher->trigger('my-channel', 'my-event', $dataNotification11);


        $className2 = get_class($wallet);
        $reflection2 = new ReflectionClass($className2);
        $modelClassName2 = $reflection2->getShortName();

        $dataNotification12 = array('user_id' => $userId, 'notification' => "$" . $proposal['cost'] .  " amount debited from your wallet for hiring influencer for the ". $userTo->title ." Job",'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),'model_class' =>$modelClassName2,'model_id' =>$userId,'seen'=>0);
        Notification::insert($dataNotification12);
        
        $dataNotification12 = [ 'count' => Notification::where('user_id', $userId)->where('seen', '0')->count(), 'user_id' => $userId, 'notification' =>"$" . $proposal['cost'] . "Amount Credit for this ". $userTo->title];
        
        $pusher->trigger('my-channel', 'my-event', $dataNotification12);


        $userFinalData = array(
            'wallet_id' => $wallet['id'],
            'amount'    => $proposal['cost'],
            'transaction_type' => UserWalletTransaction::TYPE_DABIT,
            'description' => "Amount Debit for hire " .  ($influncerUser['first_name'] . ' ' . $influncerUser['first_name']) . " Influencer", 
            'transaction_id' => "JC_" .Str::random(20),
            'created_by' => $userTo->created_by,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        );
        UserWalletTransaction::insert($userFinalData);
        
        JobProposals::where('job_id', $data['jobId'])->where('influencer_id',$data['jobhireinfluencers'])->update(['influencers_hire_status'=>'1']);
        $jobHireSucces = JobHiring::create($jobHire);

        $influencersFinalData = array(
            'wallet_id' => $influencerswallet['id'],
            'amount'    => $finalAmountJob,
            'transaction_type' => UserWalletTransaction::TYPE_CREDIT,
            'description' => "Amount Credit From " .  auth()->user()->first_name . auth()->user()->last_name, 
            'transaction_id' => "JC_" .Str::random(20),
            'created_by' => auth()->user()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        );
        UserWalletTransaction::insert($influencersFinalData);
        
        if( !empty($jobHireSucces) ){
            return response()->json(['success' => true, 'message' => "Post are apporved and funds are released"]);
        } 
        return response()->json(['success' => false, 'message' => "Something went wrong! please try again."]);
            
    } 

    public function apporvedjobpost(Request $request){
        $data = $request->all();

        $userId =  auth()->user()->id;
        $user = User::where('id', $userId)->first();
        $userType = auth()->user()->type;
        $userTo = Job::where('id', $data['jobId'])->first();
        $job = $userTo;

        if(UserPostUsrl::distinct('influencer_id')->where('job_id', $job->id)->where('influencer_id', '!=', $data['jobhireinfluencers'])->count() >= $job->influencers ){
            return response()->json(['success' => false, 'message' => "You have exceeded with maximum allowed influencers on this job."]);
        }
        $jobUrl = url('/').'/job/'.(@$data['joburl'] ? : $job['slug'] );
        
        $jobPrice = Job::where(['id' => $job['id'], 'created_by'=>$userId])->first();
        $influncerUser = User::find($data['jobhireinfluencers']);
        $email = $influncerUser->email;
        $emailUser = $user->email;
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        $settings = Setting::first();
        $totalComm = ($settings->commission / 100) * $jobPrice['price'];
     
        $adminswalletID = \App\Models\UserWallet::where('admin_id', '1')->first();
        
        if( empty($adminswalletID) ){
            $createWalletAcc = array(
                'admin_id' => '1',
            );
            $adminswalletID = \App\Models\UserWallet::create($createWalletAcc);
        }

        $adminswalletID->amount = $totalComm + $adminswalletID->amount;;
        $adminswalletID->save();
        $wallatTransaction1 = array(
           'wallet_id' => $adminswalletID['id'], 
           'commission' => $totalComm, 
           'amount' => $jobPrice['price'], 
           'influencer_id' => $influncerUser->id, 
           'job_id' => $job['id'], 
           'transaction_type' => '2', 
           'description' => 'Commission Recieved From ' . auth()->user()->first_name . ' ' . auth()->user()->last_name, 
           'transaction_id' => "JC_" .Str::random(20), 
           'created_by' => $userId, 
        );
        UserWalletTransaction::create($wallatTransaction1);

        $wallet = \App\Models\UserWallet::where('user_id', $userId)->first();
        $wallet->amount = $wallet->amount - $jobPrice['price'] ;
        $wallet->save();
            
        UserPostUsrl::where(['job_id' => $job->id, 'influencer_id' => $influncerUser->id])->update(['status' => 2]);

        $userFinalData = array(
            'wallet_id' => $wallet['id'],
            'amount'    => $jobPrice['price'],
            'transaction_type' => UserWalletTransaction::TYPE_DABIT,
            'description' => "Amount Debit for approving posts of " .  ($influncerUser['first_name'] . ' ' . $influncerUser['first_name']) . " Influencer", 
            'transaction_id' => "JC_" .Str::random(20),
            'created_by' => $userId,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        );
        UserWalletTransaction::insert($userFinalData);
        
        $influencerswallet = \App\Models\UserWallet::where('user_id', $influncerUser->id)->first();
        if(empty($influencerswallet)){
            $createWalletAcc = array(
                'user_id' => $influncerUser->id,
            );
            $influencerswallet = \App\Models\UserWallet::create($createWalletAcc);
        }
        
        $influencerswallet->amount = $influencerswallet->amount + ($jobPrice['price'] - $totalComm) ;
        $influencerswallet->save();
        $influencersFinalData = array(
            'wallet_id' => $influencerswallet['id'],
            'amount'    => ($jobPrice['price'] - $totalComm),
            'transaction_type' => UserWalletTransaction::TYPE_CREDIT,
            'description' => "Amount Credit From " .  auth()->user()->first_name . auth()->user()->last_name, 
            'transaction_id' => "JC_" .Str::random(20),
            'created_by' => auth()->user()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        );
        UserWalletTransaction::insert($influencersFinalData);

        $details = array(
            'email'     => $email,
        );
        $users = array(
            'user_name_influ' => $influncerUser['first_name'],
            'job_cost'     => ($jobPrice['price'] - $totalComm),
            'user_name'    => $user['first_name'],
            'job_name'    => $userTo->title,
            //'pdf_file' =>$filename,
            "type" => 'Credit',
        );
        dispatch(new SendEmailTransaction($details,$users));
        
        // Start RealTime Notification
        $dataNotification1 = [
            'user_id' => $data['jobhireinfluencers'], 
            'notification' => "Your post are apporved by ". auth()->user()->first_name . ' on the job <a href="'. url('job/'. $job['slug'] ) .'"'. $job['title'].'</a>',
            'model_class' => get_class($userTo),
            'model_id' => $data['jobId'],
            'seen'=>0,
        ];
        Notification::insert($dataNotification1);
        /*End of real time notification*/


        $options = array(
                'cluster' => 'ap2',
                'useTLS' => true
            );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        
        $dataNotification = [ 
            'count' => Notification::where('user_id', $data['jobhireinfluencers'])->where('seen', '0')->count(), 
            'user_id' => $userId, 
            'notification' => "Your post are apporved by ". auth()->user()->first_name . ' on the job <a href="'. url('job/'. $job['slug'] ) .'"'. $job['title'] .'</a>'
        ] ;
        $pusher->trigger('my-channel', 'my-event', $dataNotification);
        $checkUser = \App\Models\ChatRoom::where('job_id',$data['jobId'])->count();

        if( $checkUser == '0' ){
            $dataChatRoom = array(
                'job_id' => $data['jobId'],
            );
            $dataChat = \App\Models\ChatRoom::insert($dataChatRoom);
            
        } 
        $newChatRoomId = \App\Models\ChatRoom::where('job_id', $data['jobId'])->first();
        $chatRoomPartcipeant = \App\Models\ChatRoomPartcipants::where('user_id', $userId)
        ->where('job_id',$data['jobId'])
        ->count();

        if($chatRoomPartcipeant == '0'){
            $finalUserPartcipenat = array(
                'user_id' => $userId,
                'chat_room_id' => $newChatRoomId->id,
                'job_id' => $data['jobId'],
                'job_status' => "Hired By",
                'created_at' => $todayDate,
                'updated_at' => $todayDate,
            );
            $finalInfluncerPartcipenat = array(
                'user_id' => $data['jobhireinfluencers'],
                'chat_room_id' => $newChatRoomId['id'],
                'job_id' => $data['jobId'],
                'job_status' => "Hired To",
                'created_at' => $todayDate,
                'updated_at' => $todayDate,
            );
            $finalChatPartcipent = \App\Models\ChatRoomPartcipants::insert([$finalUserPartcipenat,$finalInfluncerPartcipenat]);

            //entry in message table
            $messpartcipentId = \App\Models\ChatRoomPartcipants::where('job_status', "Hired By")->first();
            
            $messUserData = array(
                'participant_id' => $messpartcipentId['id'],
                'chat_room_id' => $newChatRoomId->id,
                'reply_id' => '0',
                'message' => "You have hired for " .  $userTo->title  . " this job <a target='_blank'  href=". $jobUrl . "> " . $userTo->title . "</a>" ,
                'job_id' => $data['jobId'],
                'job_offer_status' => "Approved",
                'created_at' => $todayDate,
                'updated_at' => $todayDate,
            );
           
            $messageId = Messages::insertGetId($messUserData);

            $mesUnread = array(
                'message_id' => $messageId,
                'participant_id' => $messpartcipentId['id'],
                'chat_room_id' => $newChatRoomId->id,
                'seen_status' => '0',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );
            MessageReadMark::insert($mesUnread);

        }
        // end message

        
        $user = User::where('id', $userId)->first();

        $email = $user->email;
        $jobHire =  array(
            'job_id' => $data['jobId'], 
            'user_id' => $userTo->created_by, 
            'influencers_id' => $data['jobhireinfluencers'], 
            'job_hire_status' => JobHiring::STATUS_JOB_DONE_INFLUENCER, 
            'created_at' => $todayDate, 
            'updated_at' => $todayDate,
            'price' => @$jobPrice['price']
        );
        // $jobHireSucces = JobHiring::create($jobHire);
        $details1 = array(
            'email'     => $emailUser,
        );
        $usersDetails = array(
            'user_name_influ' => $user['first_name'],
            'job_cost'     => $jobPrice['price'],
            'user_name'    => $influncerUser['first_name'],
            'job_name'    => $userTo->title,
            //'pdf_file' =>$filename,
            "type" => 'Debit',
        );
       
        dispatch(new SendEmailTransaction($details1,$usersDetails));
       
        
        $className1 = get_class($wallet);
        $reflection1 = new ReflectionClass($className1);
        $modelClassName1 = $reflection1->getShortName();

        
        $dataNotification1 = array('user_id' => $data['jobhireinfluencers'], 'notification' => "$". ($jobPrice['price'] - $totalComm) .  " amount credited into your wallet for posting the job ". $userTo->title,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),'model_class' =>$modelClassName1,'model_id' =>$userId,'seen'=>0);
        Notification::insert($dataNotification1);
        $dataNotification11 = [ 'count' => Notification::where('user_id', $data['jobhireinfluencers'])->where('seen', '0')->count(), 'user_id' => $userId, 'notification' =>"$" . $jobPrice['price'] .  " amount credited into your wallet for posting the job ". $userTo->title];
        $pusher->trigger('my-channel', 'my-event', $dataNotification11);
        $className2 = get_class($wallet);
        $reflection2 = new ReflectionClass($className2);
        $modelClassName2 = $reflection2->getShortName();

        $dataNotification12 = array('user_id' => $userId, 'notification' => "$" . $jobPrice['price'] .  " amount debited from your wallet for approving post of " .$influncerUser['first_name']. " for the ". $userTo->title ." Job",'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),'model_class' =>$modelClassName2,'model_id' =>$userId,'seen'=>0);
        Notification::insert($dataNotification12);
        
        $dataNotification12 = [ 'count' => Notification::where('user_id', $userId)->where('seen', '0')->count(), 'user_id' => $userId, 'notification' =>"$" . $jobPrice['price'] . "Amount Credit for this ". $userTo->title];
        
        $pusher->trigger('my-channel', 'my-event', $dataNotification12);

        if(UserPostUsrl::distinct('influencer_id')->where('job_id', $job->id)->count() >= $job->influencers ){
            $job->status = 3;
            $job->save();
        }

        return response()->json(['success' => true, 'message' => "Posts are apporved and funds are released"]);
        //return response()->json(['success' => false, 'message' => "Something went wrong! please try again."]);
    
    }

    public function jobsreviews($job_id = false, $influnaserId = false, Request $request ){
        $data = $request->all();
        $authUser = auth()->user(); 
        $job_id = \Helpers::decrypt( $job_id );
        //$job_id = \Helpers::decrypt($influnaserId)
        $userBy = Job::select('created_by','title','influencers_id')->where('id', $job_id)->first();
        return view('influencer.job.addjobreviews', compact('job_id','userBy'));
     
    }

    public function submitreviews( Request $request ){
        $data = $request->all(); 
        $authUser = auth()->user()->id; 

        $validator = Validator::make($data, [
            'title' => 'required',
            'starrating' => 'required',
            "review"=> "required",
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        $jobReviews =  array(
            'rated_by' => $request->get('userTO'), //rated to
            'rated_from' => auth()->id(), 
            'order_id' => $data['jobId'], 
            'title' => $data['title'], 
            'rating' => $data['starrating'], 
            'review' => $data['review'], 
            'created_at' => $todayDate, 
            'updated_at' => $todayDate, 

        );
        $jobHireSucces = JobReviews::insert($jobReviews);
        return redirect('/add/testimonial')->with('success', 'Review Submit successfully!!');
 
    }

    public function addtestimonial(Request $request){
        return view('influencer.job.testimonial_form');
    }
    public function jobsreviews1($job_id = false, $influnaserId = false, Request $request){
        $authUser = auth()->user(); 
        $job_id = \Helpers::decrypt( $job_id );
        //$job_id = \Helpers::decrypt($influnaserId)
        $item = Job::where('id', $job_id)->update(['user_markjob_done' => '1']);
        $userBy = Job::select('created_by','title','influencers_id')->where('id', $job_id)->first();

        // Start RealTime Notification
        $className = get_class($userBy);
        $reflection = new ReflectionClass($className);
        $modelClassName = $reflection->getShortName();

        $options = array(
                'cluster' => 'ap2',
                'useTLS' => true
            );
        $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );
        $dataNotification1 = array('user_id' => $userBy->influencers_id, 'notification' => $userBy->title ." Job Confirmed Done.",'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),'model_class' =>$modelClassName,'model_id' =>$job_id,'seen'=>0);
        Notification::insert($dataNotification1);
        $dataNotification = [ 'count' => Notification::where('user_id', $userBy->influencers_id)->where('seen', '0')->count(), 'user_id' => $userBy->influencers_id, 'notification' => $userBy->title ." Job Confirmed Done."];
        
        $pusher->trigger('my-channel', 'my-event', $dataNotification);

        if(Helpers::checkUserReviews($job_id) == 0 ){ 
            return view('influencer.job.addjobreviews', compact('job_id','userBy'));
        } else {
            return redirect('/my-jobs')->with('success', 'Job successfully Done!!');
        }
    }

    public function submittestimonial(Request $request){
        $data = $request->all(); 
        $validator = Validator::make($data, [
            'name' => 'required',
            "description"=> "required",
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $authUser = auth()->user()->id; 
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        
        $arraydata =  array(
            //'jobId' => $data['jobId'], 
            'name' => $data['name'], 
            'user_id' => $authUser, 
            'description' => $data['description'], 
            'status' => 'Pending', 
            'created_at' => $todayDate, 
            'updated_at' => $todayDate, 
        );  
        Testimonial::insert($arraydata);
        
        return redirect('/my-jobs')->with('success', 'Testimonial Submit successfully!!'); 
        
      
       
    }

    public function jobcompletes($job_id = false, Request $request){
        $data = $request->all();
        
        $job_id = \Helpers::decrypt( $job_id );
        
        $user = auth()->user() ;
        $hire = JobHiring::where(['influencers_id' => $user['id'], 'job_id' => $job_id])->first() ;
        if($user['type'] == User::TYPE_INFLUENCER){
            $hire->job_hire_status = JobHiring::STATUS_JOB_DONE_INFLUENCER;
        }else{
            $hire->job_hire_status = JobHiring::STATUS_JOB_DONE_USER;
        }
        $hire->save();

        $userBy = Job::select('created_by','title','influencers_id')->where('id', $job_id)->first();
         
        // Start RealTime Notification
         $className = get_class($userBy);
        $reflection = new ReflectionClass($className);
        $modelClassName = $reflection->getShortName();

        $options = array(
                'cluster' => 'ap2',
                'useTLS' => true
            );
        $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );
         $dataNotification1 = array('user_id' => $hire->user_id == auth()->id() ? $hire->influencers_id : $hire->user_id ,  'notification' => $data['jobname'] ." Job Done by ". Auth::user()->first_name . ' ' .Auth::user()->last_name,'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),'model_class' =>$modelClassName,'model_id' =>$job_id,'seen'=>0);
        
          
           Notification::insert($dataNotification1);
       
           
        
        $dataNotification = [ 'count' => Notification::where('user_id', $userBy->created_by)->where('seen', '0')->count(), 'user_id' => $userBy->created_by, 'notification' => $data['jobname'] ." Job Done by". Auth::user()->first_name . ' ' .Auth::user()->last_name];
        

       // $pusher->trigger('my-channel', 'my-event', $dataNotification);

        if(Helpers::checkUserReviews($job_id) == 0 ){ 
            return view('influencer.job.addjobreviews', compact('job_id','userBy'));
        } else {
            return redirect('/my-jobs')->with('success', 'Job successfully Done!!');
        }
    }

    public function jobdonebyuser($job_id = false, $influnaserId = false, Request $request ){
        $data = $request->all();
        $jobId = $data['jobId'];
        $item = Job::where('id', $data['jobId'])->update(['user_markjob_done' => $data['userdone']]);
        return 1;
    }

     public function influencersCat( Request $request ) {
        $data = $request->all();
        $id = $data['catId'];
        $platform_catogery_data = User::where('category',$id)
        ->skip($data['totalCurrentResult'])
        ->take(2)
        ->get();

        $platformNextData = view('influencer.influenplatform_result', compact('platform_catogery_data'))->render();
        return $platformNextData;

     }

    public function influencersCategoriesname($category){

        $platformName = SocialPlatform::whereSlug($category)->first();
        $id = @$platformName['id'];
        
        $platform_catogery =  DB::table('user_platform_stats')
                                ->join('users','users.id','=','user_platform_stats.user_id')
                                ->join('jobs_reviews','jobs_reviews.rated_by','=','user_platform_stats.user_id')
                                ->join('user_plans','user_plans.user_id','=','user_platform_stats.user_id')
                                ->where('users.type', '2')
                                ->where('users.account_verified', '1')
                                ->orderBy('jobs_reviews.rating','DESC')
                                ->groupBy('users.id')
                                ->select('users.*','jobs_reviews.rating')
                                ->where(['platform_id' => $id])
                                ->paginate(10);
                                                      
        $categoryName = Categories::whereId($id)->first();
        return view('influencer.influencers_platform_category', compact('platformName','platform_catogery','categoryName','id'));
     }

     public function downloadZip(Request $request){
        $job=Job::where('slug',$request->slug)->first();
       
        $caption=$job->caption;
        
        $captionFile = storage_path('posts/'.$job->title.'-'.time().'.txt') ;

        $fp = fopen($captionFile, 'w');
        fwrite($fp, $job->caption);
        fclose($fp);
        $zip = new ZipArchive;
        $fileName = $job->title.'-'.time().'.zip';
        if(empty(@$job->image_video)){
          $filePath = public_path('uploads/job/No_result1636634534.png') ;
        }else{
          $filePath = public_path('uploads/job/'. $job->image_video);
        }
        if ($zip->open(storage_path('posts/'.$fileName), ZipArchive::CREATE) === TRUE)
        {
            $zip->addFile($filePath, basename(@$filePath));
            $zip->addFile($captionFile, basename($captionFile));
            $zip->close();
        }
    
        return response()->download(storage_path('posts/'.$fileName));
    }

    public function submitPostUrl(Request $request){
        $inputs = $request->all();

        $job = Job::where('id',$inputs['id'])->first();
        $user = auth()->user() ;
        UserPostUsrl::where(['job_id'=>$job->id])->where('influencer_id', $user['id'])->delete();
        foreach($request->url as $key=>$post_url)
        {
            if($post_url == ""){
                continue;
            }
            $posturl = UserPostUsrl::where(['job_id'=>$job->id,'platform_id'=>$key ])->where('influencer_id', $user['id'])->first();
            if(empty($posturl)){
                $posturl = new UserPostUsrl();
                $posturl->job_id = $job->id;
                $posturl->influencer_id = $user['id'];
            }
            $posturl->url = $post_url;
            $posturl->platform_id = $key;
            $posturl->save();
        }
        // session('success', 'Post submitted successfully.');
        return redirect('job/'. $job['slug'] )->with(['success' => 'Post submitted successfully']);
    }

    public function checkJobApplications(Request $request){

        $job = Job::where('id',$request->get('id'))->first();
        $counts = UserPostUsrl::distinct('influencer_id')->where('job_id', $job->id)->count();

        if($job['influencers'] > $counts){
            return response()->json(['success'=>true, 'message' => "Influencers are available"]);
        }
        return response()->json(['success'=>false, 'message' => "Influencers limit exceeded!, please try on other jobs"]);

    }

}
