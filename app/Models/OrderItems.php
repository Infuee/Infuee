<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;
use App\Models\Transactions;
// use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItems extends Model
{
    const STATUS_PAINDING = 0;
    const STATUS_ACCEPTED = 1;
    const STATUS_REJECTED = 2;

    protected $table = 'order_items';
        // use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'user_plan_id', 'price', 'transfer_id', 'status', 'mark_done'
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
    public function userPlan(){
        return $this->hasOne('\App\Models\UserPlans', 'id', 'user_plan_id');
    }

    public function order(){
        return $this->hasOne('\App\Models\Orders', 'id', 'order_id');
    }
    

    public function getUser(){
        $order = Orders::where('id', $this->order_id )->first();
        if(empty($order)){
            return [];
        }
        return \App\User::where('id', $order['user_id'])->first();
    }

    public function influencerAmount(){
        $userPlan = UserPlans::where('id', $this->user_plan_id)->first();
        if(empty($userPlan)){
            return $this->price;
        }
        $plan = Plans::withTrashed()->where('id', $userPlan['plan_id'])->first();
        
        $setting = Setting::first();
        $commission = @$setting['commission']?:(env('commission') ? : 10 );

        $commission = ($commission / 100 ) * $this->price ;

        return $this->price - $commission;

    }

    public function getOrderIns(){
        $order = Orders::where('id', $this->order_id)->first();
        return $this->linkify($order['description']);
    }

    public function linkify($value, $protocols = array('http', 'mail'), array $attributes = array())
    {
        // Link attributes
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr .= ' ' . $key . '="' . htmlentities($val) . '"';
        }
        
        $links = array();
        
        // Extract existing links and tags
        $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);
        
        // Extract text links for each protocol
        foreach ((array)$protocols as $protocol) {
            switch ($protocol) {
                case 'http':
                case 'https':   
                $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, "<a $attr href=\"$protocol://$link\" target=\"_blank\">$link</a>") . '>'; }, $value); break;
                case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
                case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . "\">{$match[0]}</a>") . '>'; }, $value); break;
                default:        $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
            }
        }
        
        // Insert all link
        return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
    }

    public function rated(){
        $rate = Rating::where('order_id', $this->id)->first();
        if(empty($rate)){
            return false;
        }
        return $rate;
    }
}

