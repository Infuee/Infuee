<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\UserPlans;

class Plans extends Model
{
	use SoftDeletes;
    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVATED = 2;

	protected $table = 'plans';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id','name', 'description', 'status','commission'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = false;

    public function statusBadge(){
        $array = [
            self::STATUS_ACTIVE => '<span style="width: 137px;">
                                        <span class="label font-weight-bold label-lg label-light-success label-inline">Active</span>
                                    </span>',
            self::STATUS_DEACTIVATED => '<span style="width: 137px;">
                                        <span class="label font-weight-bold label-lg label-light-danger label-inline">Deactivated</span>
                                    </span>',
        ];
        if($this->status){
            return isset($array[$this->status]) ? $array[$this->status] : $array;
        }

        return $array;
    }

    public function status($array = false){
        $array = [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_DEACTIVATED => 'Deactivated',
        ];

        if($array){
            return $array;
        }

        if($this->status){
            return isset($array[$this->status]) ? $array[$this->status] : $array;
        }

        return $array;
    }

    public function getUserPrice($shouldIncludePriceSymbol = true,$user=''){
        if(empty($user)){
            $user = auth()->user();
        }
        $userPlan = UserPlans::where(['user_id'=>$user['id'], 'plan_id'=>$this->id])->first();
        if(!@$userPlan['price']){
            return "" ;
        }
        if($shouldIncludePriceSymbol){
            return '$ '.number_format(@$userPlan['price'],2);
        }
        return number_format(@$userPlan['price'],2);
    }

    public function getUserPlan($user=''){
        if(empty($user)){
            $user = auth()->user();
        }
        return UserPlans::where(['user_id'=>$user['id'], 'plan_id'=>$this->id])->pluck('id')->first();
    }

    public function category(){
        return $this->hasOne('App\Models\PlanCategories', 'id', 'category_id');
    }
    
    function allCategory(){
        return $this->hasOne('App\Models\PlanCategories', 'id', 'category_id')->withTrashed();
    }

    public static function boot()
    {
        parent::boot();
        self::deleted(function($model){
            $UserPlans = UserPlans::where('plan_id', $model['id'])->get();
            foreach ($UserPlans as $key1 => $user_plan1) {
                $user_plan1->delete();
            }
        });

        self::restored(function($model){
            $UserPlans = UserPlans::where('plan_id', $model['id'])->withTrashed()->get();
            foreach ($UserPlans as $key2 => $user_plan2) {
                $user_plan2->restore();
            }
        });
    }
}
