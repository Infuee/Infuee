<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupons extends Model
{
    protected $table = 'coupons';
    const STATUS_PENDING = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DEACTIVATED = 3;
    use SoftDeletes;

    const STATUS_UNPAID = 0;
    const STATUS_PAID = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','code', 'description', 'type', 'discount', 'min_price', 'max_price','status','expiry_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function order(){
        return $this->hasMany('\App\Models\CouponAppliences', 'coupon_id', 'id')->where('is_redeemed', '!=', 0);
    }
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function ordercoupons(){
        return $this->hasMany('\App\Models\Orders', 'coupon_id', 'id');
    }
    

}
