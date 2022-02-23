<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = "setting";

    protected $fillable = [
        'twilio_accont_sid',
        'twilio_auth_token',
        'twilio_from',
        'google_api_key',
        'stripe_pk',
        'stripe_sk',
        'stripe_currency',
        'smtp_username',
        'smtp_password',
        'commission'
    ];

    protected $softDelete = true;
}
