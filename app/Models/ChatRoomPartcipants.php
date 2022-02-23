<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoomPartcipants extends Model
{
    protected $table = 'chat_room_participants';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'chat_room_id', 'job_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = true;

    public function user(){
        return $this->hasOne(\App\User::class, 'id' , 'user_id' );
    }

    public function getTitle(){

        $title = '';

        if($this->job_id){
            $job = Job::find($this->job_id);
            $title .= $job['title'] .'  : ' ;
        }

        if($this->user_id){
            $user = \App\User::find($this->user_id);
            $title .= $user['first_name'] . ' ' . $user['last_name']  ;
        }

        return $title ;
    }
}
