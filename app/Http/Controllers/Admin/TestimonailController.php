<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;
use App\Models\User;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TestimonailController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Jobs';
        $page_description = 'Mange Jobs';

        $query = DB::table('testimonial');

        //echo '<pre>';print_r($users); die; 

        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn() 
           ->addColumn('action', function ($testimonial) {
                $html = '';
                if($testimonial->status == 'Pending'){
                    $html = '<a href="javascript:"  class="testimonialStatus w3-button w3-green" title="Approved" data-type="testimonial" data-status="Approved" data-id="' . $testimonial->id .'">Approve</a><a href="javascript:" class="testimonialStatus w3-button w3-red" data-status="Disapproved" title="Decline" data-type="testimonial" data-id="' . $testimonial->id .'" >Decline</a>';
                }else if($testimonial->status == 'Approved'  || $testimonial->status == 'Enable'){
                    $html = '<a href="javascript:" class="testimonialStatus w3-button w3-red" data-status="Disable" title="Disable" data-type="testimonial" data-id="' . $testimonial->id .'" >Disable</a>';
                }else if($testimonial->status == 'Disapproved' || $testimonial->status == 'Disable'){
                    $html = '<a href="javascript:"  class="testimonialStatus w3-button w3-green" title="Enable" data-type="testimonial" data-status="Enable" data-id="' . $testimonial->id .'">Enable</a>';
                }
                return $html;
            })
           ->addColumn('action1', function($testimonial) {
                    return [$testimonial->id] ;
                })
           ->addColumn('created_at', function ($testimonial) {  
                return [ date('d F Y', strtotime($testimonial->created_at)) ];
            })
                     
            ->make(true);
        } 
        

        return view('admin.testimonials.list', compact('page_title', 'page_description','user','inputs'));
    }

    public function updatetestimonail(Request $request){
        $data = $request->all();
        Testimonial::where('id', $data['id'])->update(['status'=>$data['testimonialStatus'] ]);
        return 1;   
    }

    public function testimonialreviews( Request $request ){
        $data = $request->all();
        $description = Testimonial::select('description')->where('id',$data['getId'])->first();
        $des = $description['description'];
        return $des; 
    }


}
