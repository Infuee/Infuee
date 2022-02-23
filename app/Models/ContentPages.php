<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentPages extends Model
{
	
	protected $table = 'content_pages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'how_it_works', 'terms_of_service', 'privacy_policy'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
