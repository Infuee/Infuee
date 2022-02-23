<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Race extends Model
{
	use SoftDeletes;
    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVATED = 2;

	protected $table = 'race';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','status'
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
