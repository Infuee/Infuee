<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialPlatformCategory extends Model
{
    protected $table = 'social_platform_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'platform_id', 'category_id','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = false;

    public function category(){
        return $this->hasOne('App\Models\Categories', 'id', 'category_id')->where('status','1');
    }
}
