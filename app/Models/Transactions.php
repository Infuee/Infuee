<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;
use App\User;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
    protected $table = 'transactions';
    const STATUS_PENDING = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_DEACTIVATED = 3;
    // use SoftDeletes;

    const STATUS_UNPAID = 0;
    const STATUS_PAID = 1;
    const TYPE_CREDIT = 'credit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','order_id', 'order_item_id', 'user_id', 'status', 'transaction_no', 'transaction_time','type', 'amount', 'commision','influencer_id'
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
    public function influencers(){
        return $this->hasOne('App\User', 'id', 'influencer_id')->withTrashed();
    }

    public function order_items(){
        return $this->hasMany('\App\Models\OrderItems', 'order_id', 'order_id');
    }

    public function order(){
        return $this->hasOne('\App\Models\Orders', 'id', 'order_id');
    }

    public function item(){
        return $this->hasOne('\App\Models\OrderItems', 'id', 'order_item_id');   
    }

    public function getItemPrice(){
        if($this->amount){
            return $this->amount;
        }
        $orderItem = OrderItems::where('id', $this->order_item_id)->first();
        $influencerAmount = $orderItem->influencerAmount();
        return @$orderItem['price'] - $orderItem->influencerAmount();
    }

    public function getUserInfluencer($onlyArray = false){
        $user_id = $this->order['user_id'];
        $user = User::where('id', $user_id)->withTrashed()->first();
        $array = [];
        $html = '<span>';
        if(is_file(public_path("/media/users").'/'.@$user['image'])){
            $html .= '<img src="'.asset("media/users/".$user["image"]).'" style="width: 30px;">';
            $array['image'] = asset("media/users/".$user["image"]);
        }
        else{
            $html .= '<img src="media/users/blank.png" style="width: 30px;">';
            $array['image'] = asset("media/users/blank.png");
        }
        
        $html .= '<span class="user_name">'. $user["first_name"] .' ' .$user["last_name"].'</span></span>';
        $array['name'] = $user["first_name"] .' ' .$user["last_name"];
        if($onlyArray){
            return $array;
        }

        return $html;
    }
}
