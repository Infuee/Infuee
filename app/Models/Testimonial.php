<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Admin;
use App\Models\Orders;
use App\Models\OrderItems;
use App\Models\Countries;
use App\User;
use App\Models\PlanCategories;
use App\Models\Categories;
use App\Models\Plans;
use DB;


class Testimonial extends Model
{
    protected $table = 'testimonial';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','user_id','name', 'description', 'created_at','view', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = true;

    public function users()
    {
         return $this->hasOne('App\User', 'id', 'user_id');
    }
}
