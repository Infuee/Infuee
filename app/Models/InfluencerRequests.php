<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InfluencerRequests extends Model
{
	use SoftDeletes;
	protected $table = 'influencer_requests';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'username', 'followers', 'f_username', 'f_followers', 'category', 'account_verified', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'f_username', 'f_followers'
    ];

    public function getProfileImage(){
        if($this->image){
            return asset('/media/users/'.$this->image);
        }
        return asset('/media/users/default.jpg');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
