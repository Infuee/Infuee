<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use File ;
use Helpers ;

class JobReviews extends Model
{
    protected $table = 'jobs_reviews';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rated_by','rated_from', 'order_id', 'title', 'title','review','created_at','updated_at'
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
        return $this->hasOne(\App\User::class, 'id', 'influencer_id'); 
    }
}
