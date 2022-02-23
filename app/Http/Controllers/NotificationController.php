<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;
use App\Models\User;
use App\Models\Testimonial;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class NotificationController extends Controller
{
    public function index(Request $request) {
     $getAllNotificatios = Notification::where('user_id',[auth()->user()->id])
     ->orderBy('id', 'DESC')
     ->get();   
    foreach($getAllNotificatios as $notificationId ){
        Notification::where('id', $notificationId->id)->where('user_id', auth()->user()->id)->update(['seen' => '1']);
    }
     return view('user.notification',compact('getAllNotificatios'));   
    }

    public function delete(Request $request){
        $notification = Notification::whereId($request->id)->first();
        if(empty($notification)){
            return response()->json(['status'=>false, 'message' => "This notification does not exists."]);
        }
        $notification->delete();
        return response()->json(['status'=>true, 'message' => "This notification is deleted successfully" ]);
    }

    public function clear(Request $request){
        $userId =  auth()->user()->id;
        $clearNotification = Notification::where(['user_id' => $userId])->get();
        if($clearNotification->count()<= 0){
            return response()->json(['status'=>false, 'message' => "Notifications does not exists."]);
        }
        foreach($clearNotification as $org) 
        {
            $org->delete();
        }
             return response()->json(['status'=>true, 'message' => " Notification  cleared  successfully" ]); 

    }


}
