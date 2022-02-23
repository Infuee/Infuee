<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPlans extends Model
{
	use SoftDeletes;   
    protected $table = 'user_plans';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','plan_id', 'price','deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = false;

    public function plan(){
        return $this->hasOne('App\Models\Plans','id', 'plan_id');
    } 

    function allPlan(){
        return $this->hasOne('App\Models\Plans','id', 'plan_id')->withTrashed();
    }

    public function influencer(){
        return $this->hasOne('App\User','id', 'user_id');
    }

    public function allUser(){
        return $this->hasOne('App\User','id', 'user_id')->withTrashed();
    }
}
