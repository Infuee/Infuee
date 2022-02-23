<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;
use App\User;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    protected $table = 'orders';
    const STATUS_PENDING = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DEACTIVATED = 3;
    // use SoftDeletes;

    const STATUS_UNPAID = 0;
    const STATUS_PAID = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','user_id', 'description', 'billing_first_name', 'billing_last_name', 'address', 'city', 'state', 'zipcode', 'country', 'stripe_charge_id','total', 'status','order_id', 'discount_price','coupon_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id')->withTrashed();
    }

    public function order_items(){
        return $this->hasMany('\App\Models\OrderItems', 'order_id', 'id');
    }
    public function transactions(){
        return $this->hasOne('\App\Models\Transactions', 'order_id', 'id');
    }

}
