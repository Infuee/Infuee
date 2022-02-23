<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','influencer_id', 'order_id','rating','review'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = false;

    public function orderItem(){
        return $this->hasOne('\App\Models\OrderItems', 'id', 'order_id');
    }

    public function user(){
        return $this->hasOne('\App\User', 'id', 'user_id');
    }

    public function Influencer(){
        return $this->hasOne('\App\Models\User', 'id', 'influencer_id');
    }
    
}
