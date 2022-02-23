<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPlatform extends Model
{
    protected $table = 'job_platforms';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'platform_id','job_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = true;

    public function platform(){
        return $this->hasOne('App\Models\SocialPlatform', 'id', 'platform_id');
    }

}
