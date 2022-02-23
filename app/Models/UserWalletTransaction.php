<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWalletTransaction extends Model
{
    const TYPE_DABIT=1;
    const TYPE_CREDIT=2;
    

	protected $table = 'user_wallet_transaction';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wallet_id', 'amount', 'transaction_type', 'description', 'transaction_id', 'created_by','influencer_id', 'commission', 'job_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = true;

    public function wallet(){
        return $this->hasOne(\App\Models\UserWallet::class, 'id', 'wallet_id');
    }

    public function user(){
        return $this->hasOne(\App\User::class, 'id', 'created_by');   
    }
    public function influencers(){
        return $this->hasOne(\App\User::class, 'id', 'influencer_id');   
    }
    public function jobName(){
        return $this->hasOne(\App\Models\Job::class, 'id', 'job_id');   
    }
}
