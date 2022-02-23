<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlatformStats extends Model
{
    protected $table = 'user_platform_stats';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'username','platform_id','platform_social_id', 'followers'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = true;
}
