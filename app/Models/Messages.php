<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $table = 'messages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'participant_id',
        'chat_room_id',
        'reply_id',
        'message',
        'is_seen',
        'file_name', 
        'file_type',
        'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = true;


    public function child(){
        return $this->belongsTo(Messages::class,'reply_id','id');
    }

    public function senderImage(){

        return $this->participant->user->getProfileImage();

    }

    public function user(){
        return $this->hasOne(\App\User::class, 'id', 'participant_id');
    }
}
