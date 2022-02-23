<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;
	const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVATED = 2;
	protected $table = 'campaigns';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'location', 'lat', 'lng', 'website', 'image', 'status', 'created_by', 'min_age', 'max_age'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = true;

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

    public function getSlugs($value, $delimiter = '-') {
        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $value))))), $delimiter));
        if (static::whereSlug($slug)->exists()) {
            
            $slug = $this->incrementSlug($slug);
        }
    
        return $slug;
    }

    public function incrementSlug($slug) {

        $original = $slug;
    
        $count = 2;
    
        while (static::whereSlug($slug)->exists()) {
    
            $slug = "{$original}-" . $count++;
        }
    
        return $slug;
    
    }

    public function jobs(){
        return $this->hasMany(\App\Models\Job::class, 'campaign_id', 'id');
    }
    public function users(){
        return $this->hasOne(\App\User::class, 'id', 'created_by');
    }
    public function jobsCount(){
        return $this->hasMany(\App\Models\Job::class, 'campaign_id', 'id')->where('status','1');
    }

    public function logo(){

        //return asset('uploads/compaign/'.$this->image);
        if(!empty($this->image)){
            return asset('uploads/compaign/'.$this->image);
            }else{
            return asset('/media/users/default.jpg');
        }

    }

}
