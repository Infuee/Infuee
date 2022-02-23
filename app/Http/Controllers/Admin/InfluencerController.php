<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Admin;
use App\Models\InfluencerRequests;
use App\Models\SocialPlatform;
use App\User;
use DB;
use Mail;
use Pusher\Pusher;
use ReflectionClass;
use App\Models\Notification;
use Carbon\Carbon;

class InfluencerController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();

        $page_title = 'Influencer Requests'  ;
        $page_description = 'Here are Influencer Requests';
        $start = 0;
        $status = $request->get('status');
        if($status == 5){
            $query = InfluencerRequests::onlyTrashed()->with('user')->get();
        }elseif($status == 2){
             $query = InfluencerRequests::with('user')->where('status','2');
        }else{
            $query = InfluencerRequests::with('user')->where('status','0');

        }
        if($status != '' && $status != 3){
            $query = $query->where('status', (int) $status);
        }
        if($type = $request->get('type')){
            $query = $query->where('type', 'LIKE', $type);
        }
        
        if($request->ajax()){
            return datatables()->of($query->latest()->get())
            ->addIndexColumn()
            ->addColumn('name', function ($user) {
                return @$user->user['first_name'] . ' '. @$user->user['last_name'];
            })
            ->addColumn('email', function ($user) {
                return @$user->user['email'];
            })
            ->addColumn('phone', function ($user) {
                return @$user->user['phone'];
            })
            ->addColumn('phone', function ($user) {
                return @$user->user['phone'];
            })
            ->addColumn('status', function ($user) {
                return [$user->status, $user->deleted_at];
            })
            ->addColumn('action', function ($user) {
                return [$user->id, $user->status, $user->deleted_at];
            })
            ->addColumn('ins_username', function ($user) {
                if(empty($user->user)){
                    return 'NA';
                }

                $details = $user->user->getPlatformDetails( SocialPlatform::INSTAGRAM );
                if(!empty($details)){
                    if($details['username']){
                        $username = $details['username'] ;
                    }else{
                        $username = $user->user['username'] ;
                    }
                    if($username == ''){
                        $username = $details['platform_social_id'] ;
                    }
                    return $username . ' / ' . $details['followers'] ;
                }
                return 'NA';
            })
            ->addColumn('facebook_username', function ($user) {
                if(empty($user->user)){
                    return 'NA';
                }
                $details = $user->user->getPlatformDetails( SocialPlatform::FACEBOOK );
                if(!empty($details)){
                    if($details['username']){
                        $username = $details['username'] ;
                    }else{
                        $username = $user->user['username'] ;
                    }
                    if($username == ''){
                        $username = $details['platform_social_id'] ;
                    }
                    return $username . ' / ' . $details['followers'] ;
                }
                return 'NA';
            })
            ->addColumn('youtube_username', function ($user) {
                if(empty($user->user)){
                    return 'NA';
                }
                $details = $user->user->getPlatformDetails( SocialPlatform::YOUTUBE );
                if(!empty($details)){
                    if($details['username']){
                        $username = $details['username'] ;
                    }else{
                        $username = $user->user['username'] ;
                    }
                    if($username == ''){
                        $username = $details['platform_social_id'] ;
                    }
                    return $username . ' / ' . $details['followers'] ;
                }
                return 'NA';
            })
            ->addColumn('tiktok_username', function ($user) {
                if(empty($user->user)){
                    return 'NA';
                }
                $details = $user->user->getPlatformDetails( SocialPlatform::TIKTOK );
                if(!empty($details)){
                    if($details['username']){
                        $username = $details['username'] ;
                    }else{
                        $username = $user->user['username'] ;
                    }
                    if($username == ''){
                        $username = $details['platform_social_id'] ;
                    }
                    return $username . ' / ' . $details['followers'] ;
                }
                return 'NA';
            })
            ->addColumn('twitter_username', function ($user) {
                if(empty($user->user)){
                    return 'NA';
                }
                $details = $user->user->getPlatformDetails( SocialPlatform::TWITTER );
                if(!empty($details)){
                    if($details['username']){
                        $username = $details['username'] ;
                    }else{
                        $username = $user->user['username'] ;
                    }
                    if($username == ''){
                        $username = $details['platform_social_id'] ;
                    }
                    return $username . ' / ' . $details['followers'] ;
                }
                return 'NA';
            })
            
            // ->removeColumn('status')
            ->removeColumn('f_username')
            ->removeColumn('f_followers')
            ->removeColumn('user')
            ->removeColumn('account_verified')
            ->removeColumn('user_id')
            ->removeColumn('image')
            ->make(true);
        }   
        return view('admin.influencer.list', compact('page_title', 'page_description', 'user', 'inputs'));
    }

    public function view($id){        
        $page_title = 'View Influencer'  ;
        $page_description = 'View Influencer';

        $user = Auth::guard('admin')->user();
        $user_ = InfluencerRequests::where('id', $id)->with('user')->first();

        $page = 'view';
        $user_ = $user_['user'];

        $countries = \App\Models\Countries::all();

        return view('admin.users.view', compact('page_title', 'page_description', 'user', 'user_', 'page', 'countries'));
    }

    public function delete($id){

        $user_ = InfluencerRequests::find($id);

        $user_->delete();
        $email = $user_['email'];
        Mail::send('email.influencer_deleted', $confirmed = array('user_info'=>$user_), function($message ) use ($email){
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Infuee ! Account Deleted')
            ->to($email);
        });
        return response()->json(['success' => 'User deleted successfully']);

    } 

    public function banuser($id){

        $user_ = InfluencerRequests::find($id);
        $user_->status = User::STATUS_BAN;
        $user_->save();
        $email = $user_['email'];
        Mail::send('email.influencer_banned', $confirmed = array('user_info'=>$user_), function($message ) use ($email){
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Infuee ! Account Banned')
            ->to($email);
        });
        return response()->json(['success' => 'User banned successfully']);
    }

    public function restore($id){
        InfluencerRequests::withTrashed()->find($id)->restore();
        return response()->json(['success' => 'User restored successfully']);
    }

    public function deactivate($id){
        $user_ = InfluencerRequests::find($id);

        $user_->status = 2;
        $user_->save();
        $email = $user_['email'];
        Mail::send('email.influencer_deactivated', $confirmed = array('user_info'=>$user_), function($message ) use ($email){
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Infuee ! Account Deactivated')
            ->to($email);
        });
        return response()->json(['success' => 'User deactivated successfully']);
    }

    public function activate($id){
        $user_ = InfluencerRequests::find($id);

        $user_->status = 1;
        $user_->save();

        $email = $user_['email'];
        Mail::send('email.influencer_activated', $confirmed = array('user_info'=>$user_), function($message ) use ($email){
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Infuee ! Account Activated')
            ->to($email);
        });

        return response()->json(['success' => 'User activated successfully']);
    }

    public function approve($id){
        //dd($id);
        $user_ = InfluencerRequests::find($id);
        $user_->status = 1;
        $user_->save();


        /***********RealTime Notification****************/
        $className = get_class($user_);
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

        $dataNotification1 = array('user_id' => $user_['user_id'], 'notification' => "Admin has approved your influencer request. ",'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),'model_class' =>$modelClassName,'model_id' =>$id,'seen'=>0);
        Notification::insert($dataNotification1);
        $dataNotification = [ 'count' => Notification::where('user_id', $user_['user_id'])->where('seen', '0')->count(), 'user_id' => $user_['user_id'], 'notification' => "Admin has approved your influencer request. "];
        

        $pusher->trigger('my-channel', 'my-event', $dataNotification);


       

        $user = User::find($user_['user_id']);
        $user->type = User::TYPE_INFLUENCER;
        $user->followers = $user_['followers'];
        $user->username = $user_['username'];
        $user->category = $user_['category'];
        $user->account_verified = 1; 
        // $user->account_verified = $user_['account_verified']; 
        $user->save();
        $email = $user['email'];
        Mail::send('email.influencer_approve', $confirmed = array('user_info'=>$user), function($message ) use ($email){
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Infuee ! Approved')
            ->to($email);
        });

        return response()->json(['success' => 'Influencer Request accepted successfully']);
    }
    
    public function reject($id){
        $user_ = InfluencerRequests::find($id);
        $user_->status = 2;
        $user_->save();
        $user = User::find($user_['user_id']);
        $email = $user['email'];
        Mail::send('email.influencer_reject', $confirmed = array('user_info'=>$user), function($message ) use ($email){
            $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Infuee ! Rejected')
            ->to($email);
        });
        return response()->json(['success' => 'Influencer Request rejected successfully']);
    }
    
}
