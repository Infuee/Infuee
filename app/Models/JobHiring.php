<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobHiring extends Model
{
    const STATUS_ACTIVE = 1;
    const STATUS_JOB_DONE_INFLUENCER = 2;
    const STATUS_JOB_DONE_USER = 3;
    protected $table = 'job_hiring';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_id','user_id', 'influencers_id', 'price', 'job_hire_status', 'created_at','updated_at'
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
        return $this->hasOne(\App\User::class, 'id', 'influencers_id'); 
    }

    public function auser(){
        return $this->hasOne(\App\User::class, 'id', 'user_id'); 
    }    
}
