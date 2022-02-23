<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\Coupons;
use DB;

class CouponsController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Coupons';
        $page_description = 'Mange Coupons';

        $start = 0;

        $query = Coupons::withCount(['order', 'ordercoupons']);   

        if($status = $request->get('status')){
            $query = $query->where('status', 'LIKE', $status);
        }

        //echo '<pre>';print_r($query->get()); die; 
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($data) {
                return [ $data['expiry_date'], $data['status'] ];
            })
            ->addColumn('discount_c', function ($data) {
                if($data['type']!='percentage'){
                    return env('CURRENCY').''.$data['discount'];
                }else{
                    return $data['discount'].'%';
                }
            })
            ->addColumn('min_price_c', function ($data) {
                return env('CURRENCY').$data['min_price'];
            })
            ->addColumn('max_price_c', function ($data) {
                return env('CURRENCY').$data['max_price'];
            })
            ->addColumn('order_count', function ($data) {
                return [ $data['id'], $data['ordercoupons_count'] ];
            })

            ->addColumn('action', function ($data) {
                return [ $data['id'], $data['status'] ];
            })
            ->addColumn('views_coupons', function ($data) {
                return $data['id'] ;
            })
            ->make(true);
        }
        return view('admin.coupons.list', compact('page_title', 'page_description', 'user', 'inputs'));
    }   

    public function addCoupon(Request $request, $id='')
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Plan Categories';
        $page_description = 'Mange Plan Categories List';
        if(!empty($id)){
            $coupon = Coupons::where('id',$id)->first();
        } 
        // echo '<pre>';print_r($query->get()); die; 
        return view('admin.coupons.add', compact('page_title', 'page_description', 'user', 'inputs'));
    }

    public function addCoupon1(Request $request, $id='')
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Plan Categories';
        $page_description = 'Mange Plan Categories List';
        if(!empty($id)){
            $coupon = Coupons::where('id',$id)->first();
        }
        // echo '<pre>';print_r($query->get()); die; 
        return view('admin.coupons.add', compact('page_title', 'page_description', 'user', 'inputs','coupon'));
    }  
    public function saveCoupon(Request $request, $id='')
    {
        $inputs = $request->all();
        $min_price = floatval(str_replace(',' ,'', $request->input('min_price')));
        $max_price = floatval(str_replace(',' ,'', $request->input('max_price')));
        $type = $request->input('type');
        $discount = $request->input('discount');

        //Get your range
        $min = $min_price  + 0.01;
        $max = $max_price - 0.01;
         $validator = Validator::make($inputs, [
            'min_price' => [
            'required',
            function($attribute, $value, $fail) use($min_price, $max) {
                    if ($min_price < 0 ||  $min_price > $max) {
                        return $fail($attribute.' must be between 0 and max price.');
                    }
                }],
            'max_price' => [
            'required',
            function($attribute, $value, $fail) use($max_price, $min) {
                    if ($max_price < $min) {
                        return $fail($attribute.' must be greater than min price.');
                    }
                }] ,
            'discount' => [
            'required',
            function($attribute, $value, $fail) use($type, $discount,$min_price) {
                    if ($type == 'percentage' && $discount > 100) {
                        return $fail('Discount percentage only less than equal to 100.');
                    }
                    if ($type == 'flat' && $discount > $min_price) {
                        return $fail('Flat discount should be greater than order min price.');
                    }
                }],
               
            ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Plan Categories';
        $page_description = 'Mange Plan Categories List';
        if(!empty($id)){
            $coupon = Coupons::where('id',$id)->find($id);
        }else{
            $coupon = new Coupons();
        }
        $coupon->code = $inputs['code'];
        $coupon->description = $inputs['description'];
        $coupon->type = $inputs['type'];
        $coupon->discount = $inputs['discount'];
        $coupon->min_price = $inputs['min_price'];
        $coupon->max_price = $inputs['max_price'];
        $coupon->expiry_date = $inputs['expiry_date'];
        // echo '<pre>';print_r($query->get()); die; 
        if($coupon->save()){

            $request->session()->flash('success', 'Coupon '. (isset($request->user_id) ? 'updated' : 'added') .' successfully.');

        }

        return redirect()->intended('admin/coupons');
    }

    public function viewcoupounsdetails($id, Request $request){

        $inputs = $request->all();

        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Coupons';
        $page_description = 'Mange Coupons';

        $start = 0;

        $query = DB::table('coupons')
            ->join('orders', 'orders.coupon_id', '=', 'coupons.id')
            ->where('coupons.id', '=', $id)
            ->get();
        
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('code', function ($data) {
                return  @$data->code ;
            })
            ->addColumn('name', function ($data) {
                return  @$data->billing_first_name ;
            })
            ->addColumn('description', function ($data) {
                return @$data->description ;
            })
            ->addColumn('discount', function ($data) {
                return @$data->discount . '% '   ;
            })
            ->addColumn('vieworder', function ($data) {
                return @$data->id  ;
            })
            ->rawColumns(['description'])
            
            ->make(true);
        }
        return view('admin.coupons.viewCouponDetail', compact('page_title', 'page_description', 'user', 'inputs'));

    } 
}
