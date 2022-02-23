<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponAppliences extends Model
{
    protected $table = 'coupon_appliences';
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
        'cart_id','user_id', 'coupon_id', 'amount', 'is_redeemed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        ''
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function coupon(){
        return $this->hasOne('App\Models\Coupons', 'id', 'coupon_id');
    }

}
