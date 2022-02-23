<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth ;
use Socialite;
use App\User;
use App\Models\Messages;
use App\Models\ChatRoomPartcipants;
use App\Models\ChatRoom;
use App\Models\MessageReadMark;
use Helpers;
use App\Models\SellerInformation;
use Carbon\Carbon;

class MessageController extends Controller
{
    
    public function index($job_id = false, $userId = false, Request $request){
        $authUser = auth()->user(); 

        $job_id = \Helpers::decrypt( $job_id );
        
        $userId = \Helpers::decrypt( $userId );

        if($job_id || $userId){
            $condition = [];
            if($job_id){
                $condition['job_id'] = $job_id ;
            }
            if($userId){
                $condition['user_id'] = $userId ;
            }

            $participants = ChatRoomPartcipants::whereIn('chat_room_id', $authUser->myChatRooms())->where($condition)->get();

            if(count($participants) <= 0){

                $room = ChatRoom::create();
                $first = [
                    'user_id' => $authUser['id'],
                    'chat_room_id' => $room['id'],
                    'job_id' => $job_id ? : null
                ];
                $second = [
                    'user_id' => $userId,
                    'chat_room_id' => $room['id'],
                    'job_id' => $job_id ? : null
                ];
                ChatRoomPartcipants::create($first);
                ChatRoomPartcipants::create($second);
            }
        }

        $connected = $this->getConnectedUsers($authUser);
        
        if(count($connected)){
            foreach ( $connected as $key => $connect ){
                $connect['active'] = $userId == $connect['user_id'] || ( $key == 0 && !$userId ) ? true : false;
                $array = $this->getLastChatTime($connect);
                $connect['lastchattime'] = @$array['lastchattime']?: strtotime('now');
                $connect['last_message'] = @$array['last_message'];
            }
        }

        return view('chat.index', compact('connected'));
    
    }

    public function getConnectedUsers($authUser){

        return $participants = ChatRoomPartcipants::where('user_id', '!=', $authUser['id'])->whereIn('chat_room_id', $authUser->myChatRooms() )->with('user')->get();

        $allSenders = Messages::where(function ($q) use ($authUser) {
            $q->where('sender_id', $authUser['id'])
            ->orWhere('receiver_id', $authUser['id']);
        })->pluck('sender_id')->toArray();

        $allReciever = Messages::where(function ($q) use ($authUser) {
            $q->where('sender_id', $authUser['id'])
            ->orWhere('receiver_id', $authUser['id']);
        })->pluck('receiver_id')->toArray();
        $users = array_merge($allSenders, $allReciever);

        return $users;
    }

    /*
    *   Message API work
    */
    public function savemessage(Request $request, $fileInputs = false, $file = false, $mime = false)
    {

        $inputs = $request->all();
        //echo "<pre>"; print_r($inputs); die("newmessasave");
        if(isset($inputs['data'])){
            $userIdsCurr = @$inputs['data']['user_id'];
        } else{
            $userIdsCurr = @$inputs['user_id'];
        }
        $userD = User::find($userIdsCurr);
        
        $participant = ChatRoomPartcipants::with('user')
                    ->where('user_id', @$userIdsCurr)
                    ->where('chat_room_id', @$inputs['receiver_id'])
                    ->first();
        
        $raw = [
            'file_name' => '',
            'file_type' => '',
            'participant_id' => @$inputs['sender_id'],
            'chat_room_id' => @$inputs['receiver_id'],
            'reply_id' => @$inputs['reply_id'],
            'message' => @$inputs['message'],
            'seen' => 0
        ];

        if(isset($inputs['data'])){
            $raw = [
                'file_name' => @$inputs['name'],
                'file_type' => @$inputs['data']['file_type'],
                //'participant_id' => @$participant['id'],
                'participant_id' => @$inputs['data']['sender_id'],
                'chat_room_id' => @$inputs['data']['receiver_id'],
                'reply_id' => @$inputs['data']['reply_id'],
                'message' => @$inputs['data']['message'],
                'seen' => 0
            ];
        }
       

       //$sender = $participant->user;
        
        $messageCount = Messages::where(['chat_room_id' => @$inputs['receiver_id']])->count();
        $array['receiver_id'] = @$inputs['receiver_id'];
        
        $message = Messages::create($raw);

        $message= Messages::where('id',$message['id'])->with('child')->first();
        
        $array['file_name'] = $message['file_name'];
        $array['file_type'] = $message['file_type'];
        $array['participant_id'] = $message['participant_id'];
        $array['message'] = $message['message'];
        $array['reply_message'] = @$message['child']['message']?: @$message['child']['file_name'] ;
        $array['id'] = $message['id'];
        $array['reply_id'] = $message['reply_id'];
        $array['time'] = $message['created_at'];
        $array['is_first'] = @$messageCount == 0 ? true : false;
        $array['sender_image'] =  $userD->getProfileImage();
        
        // Fetch all participants IDs and make entries of each paticipant in new table.

        $participantIds = ChatRoomPartcipants::where('chat_room_id', @$inputs['receiver_id'])
                          ->where('id', '!=', $message['participant_id'] )
                          ->pluck('id')
                          ->toArray();
        foreach($participantIds as $participantData){
            $mesUnread = [];
            $mesUnread = array(
                'message_id' => $message['id'],
                'participant_id' => $participantData,
                'chat_room_id' => @$inputs['receiver_id'],
                'seen_status' => '0',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );
            MessageReadMark::insert($mesUnread);
        }

        $unreadMsgCount = MessageReadMark::where('chat_room_id',@$inputs['receiver_id'])
                    ->where('participant_id',$participantIds)
                    ->where('seen_status', 0)
                    ->count('id');


        $array['unreadMsgCount'] = $unreadMsgCount;

        return $array;
    }

    public function getUserDetails(Request $request){
        $userid = $request->get('userId');
        $room = ChatRoom::with('chatroompart')->find( @$userid );
        $user['chat'] = $this->getChat($room);
        foreach($user['chat'] as $userChat){

        $currentUserParticnt = ChatRoomPartcipants::where('user_id', auth()->user()->id )
                               ->where('chat_room_id', $userChat['chat_room_id'])
                               ->select('id')
                               ->first();

                $msgReadUpdate = array(
                        'seen_status' => 1,
                );   
        $msgReadMark = MessageReadMark::where('chat_room_id', $userChat['chat_room_id'])           
                       ->update($msgReadUpdate);                     
        }
        return response()->json($user);
    }

    private function getChat($room){
        $authUser = auth()->user(); 
        $messages = Messages::where('chat_room_id', $room['id'])->with('child')->get();
        foreach ($messages as $key => $message) {
            $userimage = ChatRoomPartcipants::where('id', $message['participant_id'])->select('user_id')->first();
            $userD = User::find($userimage['user_id']);
            $message['reply_message'] = @$message['child']['message']?: @$message['child']['file_name'] ;
            $message['sender_image'] =  $userD->getProfileImage();
            //$message['sender_image'] =  "http://infuee.local/media/users/1604912713.jpg";
        }
        return $messages;
    }

    private function getLastChatTime($participant){
        $lastmessage = Messages::where('chat_room_id', $participant['chat_room_id'])->orderBy('created_at', 'DESC')->first();
        
        if(!empty($lastmessage))  {
            $array['lastchattime'] = strtotime($lastmessage->created_at);
            $array['last_message'] = $lastmessage['message'];
            return $array;
        }
        return 0;
        
    }

    public function markseenmsgs( Request $request ){
        $data = $request->all();
        $recevierMsg = ChatRoomPartcipants::where('chat_room_id', $data['receiver_id']) 
                        ->where('id', '!=', $data['participant_id'])
                        ->first();

        $updateStatus =  MessageReadMark::where('chat_room_id', $data['receiver_id'])
          ->where('participant_id',$data['participant_id'])
          ->where('message_id',$data['id'])
          ->update(['seen_status' => 1]); 

        return  response()->json(['success' => true, 'message' => "Status updated successfully"]);

    }

    public function chatonstatus( Request $request ){
        $data = $request->all();
        $participantMsgId = ChatRoomPartcipants::where('chat_room_id', $data['chatDataStatus']['receiver_id']) 
                        ->where('id', '!=', $data['myParticipant_id'])
                        ->first();

        if($data['myParticipant_id'] == $data['chatDataStatus']['participant_id']){
            $finalUpdate = array(
                'seen_status' => '1',
            );
        
        }

        return 1;

    }

    public function useractives( Request $request ){
        $data = $request->all();
        
        if($data['status'] == 'inactive'){
            ChatRoomPartcipants::where('user_id', $data['id'])->update(['user_active_status' => 0]);
        } else {
            ChatRoomPartcipants::where('user_id', $data['id'])->update(['user_active_status' => 1]);
        }
        return  response()->json(['success' => true, 'message' => "User Active Status"]);
    }



}
