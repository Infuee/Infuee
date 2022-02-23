<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Admin;
use App\User;
use App\Models\Countries;
use App\Models\Race;
use App\Models\SocialPlatform;
use App\Models\UserPlatformStats;
use DB;
use App\Models\UserPlans;
use App\Models\Categories;
use Mail;
 
class UsersController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string',
        ]);
    }

    public function index(Request $request)
    {   

        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        
        $page_title = 'Manage Users'  ;
        $page_description = 'Manage Users';
        $start = 0;
        if($request->get('status') == 5){
            $query = User::onlyTrashed()->withCount('userPlans');
        }else{
             $query = User::withCount('userPlans')->where('type','1');

            if($status = $request->get('status')){
                $query = $query->where('status', 'LIKE', $status);
            }
        }
        
        
        if($type = $request->get('type')){
            $query = $query->where('type', 'LIKE', $type);
        }
        
        if($request->ajax()){
            // return $query->get();

            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($user) {
                return [$user->status, $user->deleted_at];
            })
            ->addColumn('action', function ($user) {
                return [$user->id, $user->status, $user->deleted_at];
            })
            ->addColumn('created_at', function ($user) {
                if(empty($user->created_at)){
                    return 'NA';
                }
               return [ date('d F Y', strtotime($user->created_at)) ];
            })
            ->addColumn('user_plans_count', function ($user) {
                return [$user->id, $user->user_plans_count, $user->type];
            })
            ->make(true);
        }   
        return view('admin.users.list', compact('page_title', 'page_description', 'user', 'inputs'));
    }

    public function influencers_index(Request $request){
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        
        $page_title = 'Manage Influencers'  ;
        $page_description = 'Manage Influencers';
        $start = 0;
        if($request->get('status') == 5){
            $query = User::onlyTrashed()->withCount('userPlans');
        }else{
             $query = User::withCount('userPlans')
                      ->where('type','2')
                      ->where('account_verified','1');

            if($status = $request->get('status')){
                $query = $query->where('status', 'LIKE', $status);
            }
        }
        
        
        if($type = $request->get('type')){
            $query = $query->where('type', 'LIKE', $type);
        }
        
        if($request->ajax()){
            // return $query->get();
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($user) {
                return [$user->status, $user->deleted_at];
            })
            ->addColumn('action', function ($user) {
                return [$user->id, $user->status, $user->deleted_at];
            })
            ->addColumn('user_plans_count', function ($user) {
                return [$user->id, $user->user_plans_count, $user->type];
            })
            ->addColumn('created_at', function ($user) {
                if(empty($user->created_at)){
                    return 'NA';
                }
               return [ date('d F Y', strtotime($user->created_at)) ];
            })

            ->addColumn('ins_username', function ($user) {
                

                $details = $user->getPlatformDetails( SocialPlatform::INSTAGRAM );
                if(!empty($details)){
                    if($details['username']){
                        $username = $details['username'] ;
                    }else{
                        $username = $user['username'] ;
                    }
                    if($username == ''){
                        $username = $details['platform_social_id'] ;
                    }
                    return $username . ' / ' . $details['followers'] ;
                }
                return 'NA';
            })
            ->addColumn('facebook_username', function ($user) {
                
                $details = $user->getPlatformDetails( SocialPlatform::FACEBOOK );
                if(!empty($details)){
                    if($details['username']){
                        $username = $details['username'] ;
                    }else{
                        $username = $user['username'] ;
                    }
                    if($username == ''){
                        $username = $details['platform_social_id'] ;
                    }
                    return $username . ' / ' . $details['followers'] ;
                }
                return 'NA';
            })
            ->addColumn('youtube_username', function ($user) {
                
                $details = $user->getPlatformDetails( SocialPlatform::YOUTUBE );
                if(!empty($details)){
                    if($details['username']){
                        $username = $details['username'] ;
                    }else{
                        $username = $user['username'] ;
                    }
                    if($username == ''){
                        $username = $details['platform_social_id'] ;
                    }
                    return $username . ' / ' . $details['followers'] ;
                }
                return 'NA';
            })
            ->addColumn('tiktok_username', function ($user) {
                
                $details = $user->getPlatformDetails( SocialPlatform::TIKTOK );
                if(!empty($details)){
                    if($details['username']){
                        $username = $details['username'] ;
                    }else{
                        $username = $user['username'] ;
                    }
                    if($username == ''){
                        $username = $details['platform_social_id'] ;
                    }
                    return $username . ' / ' . $details['followers'] ;
                }
                return 'NA';
            })
            ->addColumn('twitter_username', function ($user) {
                
                $details = $user->getPlatformDetails( SocialPlatform::TWITTER );
                if(!empty($details)){
                    if($details['username']){
                        $username = $details['username'] ;
                    }else{
                        $username = $user['username'] ;
                    }
                    if($username == ''){
                        $username = $details['platform_social_id'] ;
                    }
                    return $username . ' / ' . $details['followers'] ;
                }
                return 'NA';
            })
            ->make(true);
        }   
        return view('admin.users.influencers_list', compact('page_title', 'page_description', 'user', 'inputs'));

    }

    public function view($id){        

        $user = Auth::guard('admin')->user();
        $user_ = User::find($id);

        $page_title = $user_['first_name']. ' ' .$user_['last_name'];
        $page_description = $user_['name'] . ' Details';

        $page = 'view';
        $countries = Countries::all();
        //$categories = Categories::withTrashed()->get();
        $categories = Categories::where('status','1')->get();
        $race = Race::where('status','1')->get();
        return view('admin.users.view', compact('page_title', 'page_description', 'user', 'user_', 'page', 'countries', 'categories','race'));
    }

    public function influencersView($id){
        $user = Auth::guard('admin')->user();
        $user_ = User::find($id);

        $page_title = $user_['first_name']. ' ' .$user_['last_name'];
        $page_description = $user_['name'] . ' Details';
        $page = 'view';
        $countries = Countries::all();
        $race = Race::where('status','1')->get();
        $user_instagram_platform=UserPlatformStats::where('user_id',$id)->where('platform_id','1')->first();
        //$categories = Categories::withTrashed()->get();
        $categories = Categories::where('status','1')->get();
        $user_facebook_platform=UserPlatformStats::where('user_id',$id)->where('platform_id','2')->first();
        $user_youtube_platform=UserPlatformStats::where('user_id',$id)->where('platform_id','3')->first();
        $user_tiktok_platform=UserPlatformStats::where('user_id',$id)->where('platform_id','4')->first();
        $user_twitter_platform=UserPlatformStats::where('user_id',$id)->where('platform_id','5')->first();
        return view('admin.users.influencerView', compact('page_title', 'page_description', 'user', 'user_', 'page', 'countries', 'categories','user_facebook_platform','user_instagram_platform','user_twitter_platform','user_youtube_platform','user_tiktok_platform','race')); 
    }

    public function edit($id){
        $user = Auth::guard('admin')->user();
        $user_ = User::find($id);

        $page_title = 'Edit User'  ;
        $page_description = 'Edit User';

        $page = 'edit';
        $countries = Countries::all();
        //$categories = Categories::withTrashed()->get();
        $categories = Categories::where('status','1')->get();
        $race = Race::where('status','1')->get();
        return view('admin.users.view', compact('page_title', 'page_description', 'user', 'user_', 'page', 'countries', 'categories','race'));
    }
    public function editinfluencer($id){
        $user = Auth::guard('admin')->user();
        $user_ = User::find($id);

        $page_title = 'Edit User'  ;
        $page_description = 'Edit User';

        $page = 'edit';
        $countries = Countries::all();
        //$categories = Categories::withTrashed()->get();
        $categories = Categories::where('status','1')->get();
        $race = Race::where('status','1')->get();
        return view('admin.users.influencerView', compact('page_title', 'page_description', 'user', 'user_', 'page', 'countries', 'categories','race'));
    }

    public function store(Request $request){
        $inputs = $request->all();
        $userID = isset($request->user_id) ? $request->user_id : 0 ;
        $validator = Validator::make($inputs, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required_if:type,2',
            'email' => 'required|email|unique:users,email,' . $userID . ',id',
            'address' =>'required',
            'status' => 'required',
            'type' => 'required',
            'dob' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if(isset($request->user_id)){
             $user = User::find($request->user_id);
            if(empty($user)){
                $request->session()->flash('error', 'User is not found to update.');
                return redirect()->intended('admin/users');
            }
        }else{
            $user = new User();
        }
        if($user){
            $user->first_name = $request->get('first_name')?:$user->first_name;
            $user->last_name = $request->get('last_name')?:$user->last_name;
            $user->username = $request->get('username')?:$user->username;
            $user->phone = $request->get('phone')?:$user->phone;
            $user->country_code = $request->get('country_code')?:$user->country_code;
            $user->country = $request->get('country')?:$user->country;
            $user->city = $request->get('city')?:$user->city;
            $user->state = $request->get('state')?:$user->state;
            $user->date_of_bith = date('Y-m-d',strtotime($request->get('dob')?:$user->dob));
            $user->email = $request->get('email')?:$user->email;
            $user->address = @$inputs['address']?:$user->address;
            $user->school = @$inputs['school']?:$user->school;
            $user->category = @$inputs['category']?:$user->category;
            $user->race_id = @$inputs['race_id']?:$user->race_id;
            $user->description = @$inputs['description']?:$user->description;
            if ($request->profile_avatar) {
                $image = $request->file('profile_avatar');
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path("/media/users");
                $image->move($destinationPath , $imagename);         
                $user->image = $imagename;
            }else if($request->get('profile_avatar_remove')){
                $user->image = null;
            }
            $user->type = $request->get('type')?:$user->type;
            $user->followers = 0;
            // if($user->type == 2){
            //     $details = User::getInstaDetails($user->username);
            //     $user->followers = $details['followers'];
            // }
            if($password = $request->get('password')){
                $user->password = Hash::make($password);
            }
            
            if($request->get('status') == 4 && $request->get('status') != $user->status){
                $email = $user['email'];
                Mail::send('email.banned', $confirmed = array('user_info'=>$user), function($message ) use ($email){
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Infuee ! Banned')
                    ->to($email);  
                });
            }
            if($request->get('status') == 3 && $request->get('status') != $user->status){
                $email = $user['email'];
                Mail::send('email.deactivated', $confirmed = array('user_info'=>$user), function($message ) use ($email){
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Infuee ! Deactivated')
                    ->to($email);
                });
            }

            $user->status = $request->get('status')?:$user->status;
            $user->save();

            $request->session()->flash('success', 'User '. (isset($request->user_id) ? 'updated' : 'added') .' successfully.');
        }
        return redirect()->intended('admin/users');
    }
    public function influencerstore(Request $request){
        $inputs = $request->all();
        $userID = isset($request->user_id) ? $request->user_id : 0 ;
        $rules = [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
               // 'username' => 'required_if:type,2',
                'email' => 'required|email|unique:users,email,' . $userID . ',id',
                'address' =>'required',
                'status' => 'required',
                'type' => 'required',
                'dob' => 'required',
                ];

            $this->validate($request, $rules,);

        if(isset($request->user_id)){
             $user = User::find($request->user_id);
            if(empty($user)){
                $request->session()->flash('error', 'User is not found to update.');
                return redirect()->intended('admin/users');
            }
        }else{
            $user = new User();
        }
        if($user){
            $user->first_name = $request->get('first_name')?:$user->first_name;
            $user->last_name = $request->get('last_name')?:$user->last_name;
            $user->username = $request->get('username')?:$user->username;
            $user->facebook_username = $request->get('facebook_username')?:$user->facebook_username;
            $user->youtube_username = $request->get('youtube_username')?:$user->youtube_username;
            $user->twitter_username = $request->get('twitter_username')?:$user->twitter_username;
            $user->tiktok_username = $request->get('tiktok_username')?:$user->tiktok_username;
            $user->phone = $request->get('phone')?:$user->phone;
            $user->country_code = $request->get('country_code')?:$user->country_code;
            $user->account_verified='1';
            $user->country = $request->get('country')?:$user->country;
            $user->city = $request->get('city')?:$user->city;
            $user->state = $request->get('state')?:$user->state;
            $user->date_of_bith = date('Y-m-d',strtotime($request->get('dob')?:$user->dob));
            $user->email = $request->get('email')?:$user->email;
            $user->address = @$inputs['address']?:$user->address;
            $user->school = @$inputs['school']?:$user->school;
            $user->category = @$inputs['category']?:$user->category;
            $user->race_id = @$inputs['race_id']?:$user->race_id;
            $user->description = @$inputs['description']?:$user->description;
            $user->lat = @$inputs['lat']?:$user->lat;
            $user->lng = @$inputs['lng']?:$user->lng;
            if ($request->profile_avatar) {
                $image = $request->file('profile_avatar');
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path("/media/users");
                $image->move($destinationPath , $imagename);         
                $user->image = $imagename;
            }else if($request->get('profile_avatar_remove')){
                $user->image = null;
            }
            $user->type = $request->get('type')?:$user->type;
            $user->followers = 0;
            // if($user->type == 2){
            //     $details = User::getInstaDetails($user->username);
            //     $user->followers = $details['followers'];
            // }
            if($password = $request->get('password')){
                $user->password = Hash::make($password);
            } 
            
            if($request->get('status') == 4 && $request->get('status') != $user->status){
                $email = $user['email'];
                Mail::send('email.banned', $confirmed = array('user_info'=>$user), function($message ) use ($email){
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Infuee ! Banned')
                    ->to($email);  
                });
            }
            if($request->get('status') == 3 && $request->get('status') != $user->status){
                $email = $user['email'];
                Mail::send('email.deactivated', $confirmed = array('user_info'=>$user), function($message ) use ($email){
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Infuee ! Deactivated')
                    ->to($email);
                });
            }

            $user->status = $request->get('status')?:$user->status;
            $user->save();

            $request->session()->flash('success', 'Influencers '. (isset($request->user_id) ? 'updated' : 'added') .' successfully.');
        }
        return redirect()->intended('admin/influencers-users');
    }

    public function add(){
        $page_title = 'Add User'  ;
        $page_description = 'Add User';

        $user = Auth::guard('admin')->user();
        $user_ = new User();

        $page_title = 'Add a new user';
        $page_description = $user_['name'] . ' Details';

        $page = 'add';

        $countries = Countries::all();

        // $categories = Categories::withTrashed()->get();
        $categories = Categories::where('status','1')->get();
        $race = Race::where('status','1')->get();
        return view('admin.users.view', compact('page_title', 'page_description', 'user', 'user_', 'page', 'countries', 'categories','race'));
    }
    public function addinfluencer(){
        $page_title = 'Add User'  ;
        $page_description = 'Add User';

        $user = Auth::guard('admin')->user();
        $user_ = new User();

        $page_title = 'Add a new user';
        $page_description = $user_['name'] . ' Details';

        $page = 'add';

        $countries = Countries::all();

        //$categories = Categories::withTrashed()->get();
        $categories = Categories::where('status','1')->get();
        $race = Race::where('status','1')->get();
        return view('admin.users.influencerView', compact('page_title', 'page_description', 'user', 'user_', 'page', 'countries', 'categories','race')); 
    }

    public function delete($id){

        $user_ = User::find($id);

        $user_->delete();
       /* $user_->deleted_at = date('Y-m-d H:i:s');
        $user_->save();*/

        return response()->json(['success' => 'User deleted successfully']);

    }

    public function banuser($id){

        $user_ = User::find($id);
        $user_->status = User::STATUS_BAN;
        $user_->save();

        return response()->json(['success' => 'User banned successfully']);
    }

    public function restore($id){
        /*$user_ = User::find($id);
        $user_->status = User::STATUS_ACTIVE;
        $user_->save();*/
        User::withTrashed()->find($id)->restore();

        return response()->json(['success' => 'User restored successfully']);
    }

    public function plans($id,Request $request){
        $start = 0;
        $query = UserPlans::with(['allPlan', 'allPlan.allCategory'])->where('user_id',$id)->get();
          //echo '<pre>';print_r($query); die;
        $page_title = "User's Plans";
        $page_description = "User's Plans";

        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($row) {
                if(empty($row->allPlan)){
                return 'NA';
                } 
                return $row->allPlan->name;
            })
            ->addColumn('price', function ($row) {
                return env('CURRENCY').''.$row->price;
            })
            ->addColumn('category', function ($row) {
                if(empty($row->allPlan->allCategory->name)){
                return 'NA';
                } 
                return $row->allPlan->allCategory->name;
            })
            ->make(true);
        }   
        $user = Auth::guard('admin')->user();
        return view('admin.user_plan.list', compact('page_title', 'page_description', 'user'));

    }
    

}
