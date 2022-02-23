<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $table = 'cart';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id'
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

    public function items(){
        return $this->hasMany('\App\Models\CartItems', 'cart_id', 'id');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function cart_items(){
        return $this->hasMany('\App\Models\CartItems', 'cart_id', 'id');
    }

    public function coupon(){
        return $this->hasOne('App\Models\CouponAppliences', 'cart_id', 'id')->where('is_redeemed',0);   
    }

    public static function my($user_id = false){
        if(!$user_id){
            $user_id = auth()->user()->id ;
        }
        return self::where('user_id', $user_id )->with(['items','items.userPlan', 'items.userPlan.influencer', 'items.userPlan.plan', 'coupon', 'coupon.coupon'])->first();
    }

    public static function getTotal(){
        $cart = self::where('user_id', auth()->user()->id)->first();
        if(empty($cart)){
            return 0;
        }
        return $cart->total();
    }

    public function grandTotal(){
        $total = $this->total();
        $discountAmount = $this->discountAmount();
        return $total - $discountAmount; 
    }

    public function total(){
        if($this->items && count($this->items)){
            $total = 0;
            foreach ($this->items as $key => $item) {
                $total += $item['userPlan']['price'];
            }
            return $total;
        }
        return 0;
    }

    public function discountAmount(){
        return @$this->coupon['amount'] ? : 0;
    }

    public function empty(){
        if($this->items && count($this->items)){
            $total = 0;
            foreach ($this->items as $key => $item) {
                $item->delete();
            }
        }
    }

    public static function rfs($user_id = false){
        if(!$user_id){
            $user_id = auth()->user()->id ;
        }
        $cart = self::my($user_id);
        if(@$cart['coupon']){
            $appliedCoupon = $cart['coupon'];
            $coupon = $appliedCoupon['coupon'];
            $total = $cart->total();

            if(strtotime('now')>strtotime($coupon['expiry_date'])){
                $appliedCoupon->delete();
            }
            
            // $isApplied = CouponAppliences::where(['cart_id'=> $cart['id'], 'coupon_id'=> $coupon['id']])->count();
            // if($isApplied){
            //     $appliedCoupon->delete();
            // }

            if($total < $coupon['min_price']){
                $appliedCoupon->delete();
            }            

            if($coupon['type'] == 'percentage'){
                $discount = ($coupon['discount'] / 100) * $total;
                if($discount > $coupon['max_price']){
                    $discount = $coupon['max_price'];
                }
            }
            if($coupon['type'] == 'flat'){
                $discount = $coupon['discount'] ;
                if($discount > $coupon['max_price']){
                    $discount = $coupon['max_price'];
                }
            }
            $appliedCoupon->amount = $discount;
            $appliedCoupon->save();
        }
        return false;
    }

    public static function itemsCount(){
        $cart = self::my();
        return $cart['items'] ? count($cart['items']) : 0;
    }

}
