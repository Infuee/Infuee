<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialPlatform extends Model
{
    use SoftDeletes;
	const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVATED = 2;

    const INSTAGRAM = 1 ;
    const FACEBOOK = 2 ;
    const YOUTUBE = 3 ;
    const TIKTOK = 4 ;
    const TWITTER = 5 ;


	protected $table = 'social_platform';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = false;

    public function status($array = false){
        $array = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DEACTIVATED => 'Archived',
        ];

        if($array){
            return $array;
        }


        if($this->status){
            return isset($array[$this->status]) ? $array[$this->status] : $array;
        }

        return $array;
    }

    public function categories(){
        return $this->hasMany('App\Models\SocialPlatformCategory', 'platform_id', 'id');
    }

    public function categoriesArray(){
        return $this->categories()->pluck('category_id')->toArray();
    }

    public function categoriesSpan(){
        $array = ['primary', 'warning', 'danger', 'info', 'secondary', 'success'];
        $html = '';
        foreach($this->categories as $key => $category ){
            $html .= '<span class="badge badge-'. $array[$key % 5] .'">'.@$category['category']['name'].'</span>';
        }
        return $html ;
    }

    public function isDefaultPlatform(){
        $array = [self::INSTAGRAM, self::FACEBOOK , self::YOUTUBE, self::TIKTOK, self::TWITTER];

        return in_array($this->id, $array) ;
    }

}
