<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Plans;
use App\Models\UserPlans;

class PlanCategories extends Model
{
    use SoftDeletes;	
    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVATED = 2;

	protected $table = 'plan_categories';
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

    public function statusBadge(){
        $array = [
            self::STATUS_ACTIVE => '<span style="width: 137px;">
                                        <span class="label font-weight-bold label-lg label-light-success label-inline">Active</span>
                                    </span>',
            self::STATUS_DEACTIVATED => '<span style="width: 137px;">
                                        <span class="label font-weight-bold label-lg label-light-danger label-inline">Archived</span>
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

    public function plans(){
        return $this->hasMany('\App\Models\Plans', 'category_id', 'id')->where(function($q){
            if(auth()->user()){
                $q->where('user_id', '=', auth()->user()->id )->orWhere('user_id', '=', null)->orWhere('user_id', \Session::get('influencer'));
            }
        });
    }
    public function plansFront(){
        return $this->hasMany('\App\Models\Plans', 'category_id', 'id')->where(function($q){
            if($influencer = \Session::get('influencer')){
                $q->where('user_id', '=', null)->orWhere('user_id', $influencer );
            }
        });
    }
    
    function allPlans(){
        return $this->hasMany('\App\Models\Plans', 'category_id', 'id')->withTrashed();
    }

    public static function boot()
    {
        parent::boot();
        self::deleted(function($model){
            $Plans = Plans::where('category_id', $model['id'])->get();
            
            foreach ($Plans as $key => $plan) {
                $plan->delete();
                $UserPlans = UserPlans::where('plan_id', $plan->id)->get();
                foreach ($UserPlans as $key1 => $user_plan) {
                    $user_plan->delete();
                }
            }
            
        });

        self::restored(function($model){
            $Plans = Plans::where('category_id', $model['id'])->withTrashed()->get();
            // echo '<pre>';print_r($Plans); die;
            foreach ($Plans as $key => $plan) {
                $plan->restore();
                $UserPlans = UserPlans::where('plan_id', $plan->id)->withTrashed()->get();
                foreach ($UserPlans as $key1 => $user_plan) {
                    $user_plan->restore();
                }
            }
            
        });
    }
}
