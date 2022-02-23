<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
	protected $table = 'user_wallet';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','amount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = true;
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function user(){
        return $this->hasOne(\App\User::class, 'id', 'user_id');
    }

    public function transactions(){
        return $this->hasMany(\App\Models\UserWalletTransaction::class, 'wallet_id', 'id');   
    }

}
