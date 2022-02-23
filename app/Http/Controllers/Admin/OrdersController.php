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
use DB;


class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Manage Orders';
        $page_description = 'Manage Orders';

        $start = 0;

        
        $query = Orders::withCount('order_items')->with('user');       
         
        // $query = $query->where('allCategory.id', 1);
        // echo '<pre>';print_r($query->get()); die; 
        /*if($status = $request->get('status')){
            $query = $query->where('status', 'LIKE', $status);
        }*/
        $from = $request->get('from');
        $to = $request->get('to');
        // echo $from.' '.$to; die;
        if(!empty($from) && !empty($to)){
            $query->whereBetween('orders.created_at', [$from.' 00:00:00', $to.' 23:59:59']);
            // $query->where('orders.created_at','2020-10-22 05:00:00');
        }
        // dd(DB::getQueryLog());
        // echo $query->toSql(); die;

        // echo '<pre>';print_r($inputs); die; 
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($user) {  
                return [ $user->user->id, $user->user->first_name . ' '. $user->user->last_name ];
            })
            ->addColumn('billing_name', function ($user) {
                // $user->order_items
                return $user->billing_first_name . ' '. $user->billing_last_name;
            })
            ->addColumn('order_items_count', function ($data) {
                return [ $data['id'], $data['order_items_count'] ];
            })
            ->addColumn('created_at_c', function ($data) {
                return $data['created_at'];
            })
            ->addColumn('action', function ($data) {
                return [ $data['id'], $data['status'] ];
            })
            ->make(true);
        }  
        
        $PlanCategories  = PlanCategories::get();
        return view('admin.orders.list', compact('page_title', 'page_description', 'user', 'inputs','PlanCategories'));
    }   

    public function orderItems(Request $request, $id)
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Order Items';
        $page_description = 'Order Items';

        $start = 0;

        DB::statement(DB::raw('set @rownum='.$start));        
        $query = OrderItems::select([ DB::raw('@rownum := @rownum + 1 AS rank'),'order_items.id', 'order_items.order_id','order_items.user_plan_id','order_items.price','order_items.status'])->with('userPlan','userPlan.allUser','userPlan.plan','userPlan.plan.category');        
        $query = $query->where('order_items.order_id', '=', $id);
        // echo '<pre>';print_r($query->get()); die; 
        if($request->ajax()){
            return datatables()->of($query)
            ->addColumn('plan_name', function ($data) {
                return $data->userPlan->plan->name;
            })
            ->addColumn('influencer_name', function ($data) {
                return $data->userPlan->allUser->first_name;
            })
            ->addColumn('cur_price', function ($data) {
                return env('CURRENCY').' '.$data->price;
            })
            ->addColumn('status', function ($data) {
                return $data['status'];
            })
            ->make(true);
        }  
        // echo '<pre>';print_r($query->get()); die; 
        return view('admin.orders.items', compact('page_title', 'page_description', 'user', 'inputs'));
    } 

    public function viewOrder($id){
        $user = Auth::guard('admin')->user();
        $orders = Orders::with('order_items','transactions','order_items.userPlan','order_items.userPlan.allUser','order_items.userPlan.allPlan','order_items.userPlan.allPlan.allCategory');        
        $orders = $orders->find($id);
        // $orders = $orders->where('orders.id', '=', $id)->get()->all();
        // echo '<pre>';print_r($orders->get()); die;
        $page_title = 'View Order';
        $page_description = 'View Order';
        /*$user_id = $orders[0]->user_id;
        $user_ = User::find($user_id);*/

        $page = 'view';
        $countries = Countries::all();
        return view('admin.orders.view', compact('page_title', 'page_description', 'user', 'page', 'countries','orders'));
    }  
}
