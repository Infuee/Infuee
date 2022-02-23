<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Countries;
use App\Models\Categories;
use App\Models\PlanCategories;
use App\Models\Plans;
use App\Models\UserPostUsrl;
use App\Models\UserPlans;
use App\Models\Orders;
use App\Models\Campaign;
use App\Models\OrderItems;
use App\Models\InfluencerRequests;
use App\Models\Race ;
use App\Models\SocialPlatform ;
use Illuminate\Http\Request;
use Helpers;
use Mail;
use JWTAuth;
use DB;
use File;
use Tymon\JWTAuth\Exceptions\JWTException;
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use App\Models\UserPlatformStats;
use App\Models\Job;
use App\Models\JobPlatform;
use App\Models\JobReviews ;


class CampaignJobController extends Controller
{

    
    /**
    * @OA\Post(
    ** path="/api/add-job",
    *   tags={"User"},
    *   summary="Add Job New Job",
    *   operationId="add-job",
    *   security={{ "apiAuth": {} }},
    *   @OA\Response(
    *      response=200,
    *      description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *   ),
    *   @OA\Response(
    *      response=400,
    *      description="Bad Request"
    *   ),
    *   @OA\Response(
    *      response=404,
    *      description="not found"
    *   ),
    *   @OA\Response(
    *      response=403,
    *      description="Forbidden"
    *   ),
    *    @OA\Parameter(
    *      name="title",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="description",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="platforms",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *          type="array",
        *      @OA\Items(
        *          type="array",
        *          @OA\Items()
        *       ),
        *      )
    *   ),
    *   @OA\Parameter(
    *      name="category",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="influencers",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="minutes",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="seconds",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="price",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *)
    **/
    
    public function saveJob(Request $request) {
        try {
            $inputs = $request->all();
            $image = $request->file('file');
            $validator = Validator::make($inputs, [
                'title' => 'required',
                "description"=> "required",
                "platforms" => "required",
                "category" =>  "required",
                "influencers" => "required",
                "minutes" => "required_if:seconds,==,NULL",
                "seconds" => "required_if:minutes,==,NULL",
                "price" => "required"
            ]);
            
            if ($validator->fails()) {
                $response['code'] = 404;
                $response['status'] = $validator->errors()->first();
                $response['message'] = "missing parameters";
                return response()->json($response);
            }   
            $user = JWTAuth::parseToken()->authenticate();
      
            $job = new Job();
            // $job->campaign_id = @$campaign['id'] ? : $job->campaign_id ;
            $job->title = $inputs['title'];
            $job->slug = $job->getSlugs($inputs['title']);
            $job->status = @$inputs['status'] ? : Job::STATUS_ACTIVE ;
            $job->description = $inputs['description'];
            $job->duration = ((int) $inputs['minutes'] * 60 )  + (int) $inputs['seconds'];
            $job->category_id = $inputs['category'];
            $job->influencers = $inputs['influencers']?:0;
            $job->promo_days = @$inputs['promo_days']?:0;
            $job->price = $inputs['price'];
            $job->created_by = $user->id;
            $job->location = @$inputs['location'];
            $job->min_age = @$inputs['min_age'];
            $job->max_age = @$inputs['max_age'];
            $job->minimum_followers = json_encode(@$inputs['minimum_followers']);
            $uploadedfile = $request->file('image');
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
            
            if($job->save()){
                JobPlatform::where('job_id', $job->id)->delete();
                if($inputs['platforms']) {
                    $platformsArr = explode(",",$inputs['platforms']);
                    if(count($platformsArr) > 0) {
                        foreach($platformsArr as $platform){
                            JobPlatform::create(['platform_id'=>$platform, 'job_id' => $job->id]);
                        }
                    }
                }
                $response['code'] = 200;
                $response['status'] = "Success";
                $response['message'] = "Job successfully added";
                return response()->json($response);
            } 
        }catch (\Exception $ex) {
            return response()->json(['code'=>200,'status'=>'Fail','message' => $ex->getMessage()]);
        }

    }
    
    /**
    * @OA\Post(
    ** path="/api/create-campaign",
    *   tags={"User"},
    *   summary="Create Campaign",
    *   operationId="create-campaign",
    *   security={{ "apiAuth": {} }},
    *   @OA\Response(
    *      response=200,
    *      description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *   ),
    *   @OA\Response(
    *      response=400,
    *      description="Bad Request"
    *   ),
    *   @OA\Response(
    *      response=404,
    *      description="not found"
    *   ),
    *   @OA\Response(
    *      response=403,
    *      description="Forbidden"
    *   ),
    *   @OA\Parameter(
    *      name="title",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *   @OA\Parameter(
    *      name="description",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="location",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="website",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="id",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="lat",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *   @OA\Parameter(
    *      name="lng",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   )
    *)
    **/
    public function create_campaign(Request $request){
        $inputs = $request->all();
        $id = $request->get('id');
        $user = JWTAuth::parseToken()->authenticate();
        $validator = Validator::make($inputs, [
            'title' => 'required',
            "description"=> "required",
            "location"=> "required",
            "lat"=> "required",
            "lng"=> "required",
        ]);
        if ($validator->fails()) {
                $response['code'] = 404;
                $response['status'] = $validator->errors()->first();
                $response['message'] = "missing parameters";
                return response()->json($response);
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
        $campaign->lat = '28.6139391';
        $campaign->lng = '77.2090212';
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
            $response['code'] = 200;
            $response['status'] = "Success";
            $response['message'] = ($id ? 'updated' : 'added') .' successfully.';
            return response()->json($response);
        }
    }
    /**
    * @OA\Get(
     ** path="/api/campaigns-list",
     *   tags={"User"},
     *   summary="Campaigns-List",
     *   operationId="campaigns-list",
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/
     
     public function campaigns_list(Request $request){
        $user=[];
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch(\Exception $e){}

        $campaigns = Campaign::with(['jobs'])->where(function($q) use ($user){
            $q->where('created_to', null)->orWhere('created_to', @$user['id']);
        })->whereStatus(Campaign::STATUS_ACTIVE)->paginate(20);
        foreach($campaigns as $key => $campaign){
            $campaigns[$key]['activeJobsCount'] = count($campaign['jobsCount']);
            if($campaigns[$key]['activeJobsCount'] == 0){
                unset($campaigns[$key]);
            }
        }

        $message= "Campaigns List";
        return $this->respondData(200,$message,['campaigns' => $campaigns]);
     }

      /**
     * @OA\Get(
     ** path="/api/my-campaigns-list",
     *   tags={"User"},
     *   summary="My-Campaigns-List",
     *   operationId="my-campaigns-list",
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/
     public function my_campaigns_list(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $campaigns = Campaign::with(['jobs'])->whereStatus(Campaign::STATUS_ACTIVE)->where('created_by', $user['id'])->paginate(20);
        $token = \Tymon\JWTAuth\Facades\JWTAuth::getToken() ;
        $token = '?token='.$token ;
        
        foreach($campaigns as $key => $campaign){
            $campaigns[$key]['url'] = url('campaign/'. @$campaign['slug'] ).$token ;
            $campaigns[$key]['image'] = @$campaign->logo() ;
            $campaigns[$key]['activeJobsCount'] = count($campaign['jobsCount']);    
        }

        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = "Campaign List";
        $response['create_campaign_url'] = url("create/campaign").$token;
        $response['data'] = $campaigns;
        return response()->json($response);

     }
    /**
    * @OA\Post(
    ** path="/api/submit-post-url",
    *   tags={"User"},
    *   summary="Submit Post Url",
    *   operationId="submit-post-url",
    *   security={{ "apiAuth": {} }},
    *   @OA\Response(
    *      response=200,
    *      description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *   ),
    *   @OA\Response(
    *      response=400,
    *      description="Bad Request"
    *   ),
    *   @OA\Response(
    *      response=404,
    *      description="not found"
    *   ),
    *   @OA\Response(
    *      response=403,
    *      description="Forbidden"
    *   ),
    * @OA\Parameter(
    *      name="url",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *          type="array",
        *      @OA\Items(
        *          type="array",
        *          @OA\Items()
        *       ),
        *      )
    *   ),
    *    @OA\Parameter(
    *      name="job_id",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *   @OA\Parameter(
    *      name="influencer_id",
    *      in="query",
    *      required=true,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *)
    **/
    
   public function submit_post_url(Request $request){
         $inputs = $request->all();


         $job = Job::where('id',$inputs['job_id'])->first();
        $user = JWTAuth::parseToken()->authenticate();
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


   /**
    * @OA\Get(
    ** path="/api/browse-jobs",
    *   tags={"User"},
    *   summary="Browse Jobs",
    *   operationId="browse-jobs",
    *   @OA\Response(
    *      response=200,
    *      description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *   ),
    *   @OA\Response(
    *      response=400,
    *      description="Bad Request"
    *   ),
    *   @OA\Response(
    *      response=404,
    *      description="not found"
    *   ),
    *   @OA\Response(
    *      response=403,
    *      description="Forbidden"
    *   ),
    *   @OA\Parameter(
    *      name="price_min",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *   @OA\Parameter(
    *      name="price_max",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *   @OA\Parameter(
    *      name="race",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *          type="array",
    *          @OA\Items(
    *              type="array",
    *              @OA\Items()
    *          ),
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="categories",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *          type="array",
    *          @OA\Items(
    *              type="array",
    *              @OA\Items()
    *          ),
    *      )
    *   ),
    *   @OA\Parameter(
    *      name="age",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="lat",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="lng",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="radious",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *   @OA\Parameter(
    *      name="raiting_filter",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="string"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="price_order",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *    @OA\Parameter(
    *      name="platforms",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *          type="array",
    *          @OA\Items(
    *              type="array",
    *              @OA\Items()
    *          ),
    *      )
    *   ),
    *)
    **/

   public function browseJobs(Request $request){
        $user = false;
        $createJobUrl = "";
        $token = "";
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $token = \Tymon\JWTAuth\Facades\JWTAuth::getToken() ;
            $token = '?token='.$token ;
            $createJobUrl = url('create-job').$token;
        }catch(\Exception $e){}
        $query = Job::whereNull('campaign_id')->where(function($q) use ($user){
            $q->where('created_to', null)->orWhere('created_to', @$user['id']);
        })->whereStatus(Job::STATUS_ACTIVE);
        $categories = Categories::where('status', 1)->pluck('name', 'id')->toArray();
        $race = Race::where('status', 1)->pluck('title', 'id')->toArray();
        $platforms = SocialPlatform::where('status', SocialPlatform::STATUS_ACTIVE)->paginate(10);

        $lowPrice = $request->get('price_min')?:0;
        $maxPrice = Job::orderBy('price', 'DESC')->pluck('price')->first();
        $maxPrice = $maxPrice ?:0;
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
            $query = $query->where('min_age', '>=', $age);
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
                $pla = SocialPlatform::whereIn('slug', $platforms_)->orWhereIn('id', $platforms_)->pluck('id')->toArray();
                $userStats  = JobPlatform::whereIn('platform_id', $pla)->pluck('job_id')->toArray();
                $query = $query->whereIn('id', $userStats);
            }

        }

        $jobs = $query->paginate(20);
        foreach($jobs as $key => $job){
            $jobs[$key]['image'] = $job->logo();
            $jobs[$key]['slug'] = url('job/'. $job['slug'] ). $token;
            $jobs[$key]['description'] = strip_tags( $job['description'] );
            $jobs[$key]['created_at'] = $job->created_at->diffForHumans();
            $jobs[$key]['duration'] = ($job->getMinutes()? $job->getMinutes().' min':'') .' '. ($job->getSeconds()? $job->getSeconds().' sec':'') ;
            $jobs[$key]['minimum_followers'] = (array) json_decode($job->minimum_followers ) ;
            $jobs[$key]['platforms'] =  JobPlatform::with('platform')->where('job_id', $job['id'])->get();
        }

        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = "Job List";
        $response['data'] = $jobs;
        $response['create_job_url'] = $createJobUrl;
        $response['max_price'] = $maxPrice;
        
        return response()->json($response);

    }


    /**
    * @OA\Get(
    ** path="/api/campaigns",
    *   tags={"User"},
    *   summary="Browse campaigns",
    *   operationId="browse-campaigns",
    *   @OA\Response(
    *      response=200,
    *      description="Success",
    *      @OA\MediaType(
    *           mediaType="application/json",
    *      )
    *   ),
    *   @OA\Response(
    *      response=400,
    *      description="Bad Request"
    *   ),
    *   @OA\Response(
    *      response=404,
    *      description="not found"
    *   ),
    *   @OA\Response(
    *      response=403,
    *      description="Forbidden"
    *   ),
    *    @OA\Parameter(
    *      name="page",
    *      in="query",
    *      required=false,
    *      @OA\Schema(
    *           type="integer"
    *      )
    *   ),
    *)
    **/
    public function campaigns(Request $request){

        $token = "";
        $user = [];
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $token = \Tymon\JWTAuth\Facades\JWTAuth::getToken() ;
            $token = '?token='.$token ;
            $createJobUrl = url('create-job').$token;
        }catch(\Exception $e){}

        $campaigns = Campaign::with(['jobsCount'])->whereStatus(Campaign::STATUS_ACTIVE)->get();

        foreach($campaigns as $key => $campaign){
            if( count($campaign['jobsCount']) || @$user['id'] == @$campaign['created_by']){
                $ids[] = $campaign['id'];
            }
        }
        $campaigns = Campaign::with(['jobs'])->whereIn('id', $ids)->whereStatus(Campaign::STATUS_ACTIVE)->paginate(20);

        foreach($campaigns as $key => $campaign){
            $campaigns[$key]['url'] = url('campaign/'. @$campaign['slug'] ).$token ;
            $campaigns[$key]['image'] = @$campaign->logo() ;
            $campaigns[$key]['activeJobsCount'] = count($campaign['jobsCount']);
            
            foreach($campaign['jobs'] as $key1 => $job){
                $campaigns[$key]['jobs'][$key1]['image'] = $job->logo();
                $campaigns[$key]['jobs'][$key1]['slug'] = url('job/'. $job['slug'] ). $token;
                $campaigns[$key]['jobs'][$key1]['description'] = strip_tags( $job['description'] );
                $campaigns[$key]['jobs'][$key1]['created_at'] = $job->created_at->diffForHumans();
                $campaigns[$key]['jobs'][$key1]['duration'] = ($job->getMinutes()? $job->getMinutes().' min':'') .' '. ($job->getSeconds()? $job->getSeconds().' sec':'') ;
                $campaigns[$key]['jobs'][$key1]['minimum_followers'] = (array) json_decode($job->minimum_followers ) ;
            }
            unset($campaigns[$key]['jobsCount']);

        }

        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = "Campaign List";
        $response['create_campaign_url'] = url("create/campaign").$token;
        $response['data'] = $campaigns;
        return response()->json($response);

    }

    /**
     * @OA\Get(
     ** path="/api/my-jobs",
     *   tags={"User"},
     *   summary="my-jobs",
     *   security={{ "apiAuth": {} }},
     *   operationId="my-jobs",
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/
    public function myJobs(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $token = "";
        try{
            $token = \Tymon\JWTAuth\Facades\JWTAuth::getToken() ;
            $token = '?token='.$token ;
            $createJobUrl = url('create-job').$token;
        }catch(\Exception $e){}
        $userJobIds = Job::where('created_by', $user->id)->pluck('id')->toArray();

        $activeJobsIds = UserPostUsrl::where(function($q) use ($userJobIds, $user){
            $q->whereIn('job_id', $userJobIds)->orWhere('influencer_id', $user->id);
        })->where('status', 0)->pluck('job_id')->toArray();

        $completedJobsIds = UserPostUsrl::where(function($q) use ($userJobIds, $user){
            $q->whereIn('job_id', $userJobIds)->orWhere('influencer_id', $user->id);
        })->where('status', 2)->pluck('job_id')->toArray();
        
        $jobs = Job::whereNull('campaign_id')->whereNotIn('id', $completedJobsIds )->whereNotIn('id', $activeJobsIds )->whereStatus(Job::STATUS_ACTIVE)->latest()->where('created_by', auth()->id())->paginate(20, ['*'], 'jobs');

        foreach($jobs as $key => $job){
            $jobs[$key]['image'] = $job->logo();
            $jobs[$key]['slug'] = url('job/'. $job['slug'] ). $token;
            $jobs[$key]['description'] = strip_tags( $job['description'] );
            $jobs[$key]['created_at'] = $job->created_at->diffForHumans();
            $jobs[$key]['duration'] = ($job->getMinutes()? $job->getMinutes().' min':'') .' '. ($job->getSeconds()? $job->getSeconds().' sec':'') ;
            $jobs[$key]['minimum_followers'] = (array) json_decode($job->minimum_followers ) ;
        }

        $completedjobs = Job::whereIn('id', $completedJobsIds)->paginate(20, ['*'], 'completedjobs');

        foreach($completedjobs as $key => $job){
            $completedjobs[$key]['image'] = $job->logo();
            $completedjobs[$key]['slug'] = url('job/'. $job['slug'] ). $token;
            $completedjobs[$key]['description'] = strip_tags( $job['description'] );
            $completedjobs[$key]['created_at'] = $job->created_at->diffForHumans();
            $completedjobs[$key]['duration'] = ($job->getMinutes()? $job->getMinutes().' min':'') .' '. ($job->getSeconds()? $job->getSeconds().' sec':'') ;
            $completedjobs[$key]['minimum_followers'] = (array) json_decode($job->minimum_followers ) ;
        }

        $activeJobs = Job::whereIn('id', $activeJobsIds)->paginate(20, ['*'], 'activeJobs') ;
        foreach($activeJobs as $key => $job){
            $activeJobs[$key]['image'] = $job->logo();
            $activeJobs[$key]['slug'] = url('job/'. $job['slug'] ). $token;
            $activeJobs[$key]['description'] = strip_tags( $job['description'] );
            $activeJobs[$key]['created_at'] = $job->created_at->diffForHumans();
            $activeJobs[$key]['duration'] = ($job->getMinutes()? $job->getMinutes().' min':'') .' '. ($job->getSeconds()? $job->getSeconds().' sec':'') ;
            $activeJobs[$key]['minimum_followers'] = (array) json_decode($job->minimum_followers ) ;
        }

        $response['code'] = 200;
        $response['status'] = "Success";
        $response['message'] = "My Jobs List";
        $response['create_campaign_url'] = url("create/campaign").$token;
        $response['create_job_url'] = $createJobUrl ;
        $response['data'] = ['jobs' => $jobs, 'completedjobs' => $completedjobs, 'activeJobs' => $activeJobs ];
        return response()->json($response);
    }

}
