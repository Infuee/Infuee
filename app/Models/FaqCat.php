<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;
use Illuminate\Database\Eloquent\SoftDeletes;

class FaqCat extends Model
{
	use SoftDeletes;
	protected $table = 'faq_cat';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'cat_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public $timestamps = false;

    public function ques_ans()
    {
        // return $this->belongsTo(\App\Models\Faq::class,"id","cat_id");
        return Faq::where('cat_id', $this->id)->get();
    }

    public function faqs(){
        return $this->hasMany('\App\Models\Faq', 'cat_id', 'id');
    }
}
