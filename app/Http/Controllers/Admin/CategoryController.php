<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Admin;
use App\Models\InfluencerRequests;
use App\User;
use App\Models\PlanCategories;
use App\Models\Categories;
use App\Models\Plans;
use App\Models\SocialPlatform;
use App\Models\SocialPlatformCategory;
use DB;
use File;
use Helpers;
use App\Mail\AddPlanCatSendEmail;
use App\Jobs\AddPlanCatJob;
use Mail;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Plan Categories';
        $page_description = 'Mange Plan Categories List';

        $start = 0;

        if($request->get('status') == 2){
            $query = PlanCategories::onlyTrashed()->withCount('plans'); 
        }else{
            $query = PlanCategories::withCount('plans');        
            if($status = $request->get('status')){
                $query = $query->where('status', 'LIKE', $status);
            }
        }
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('plans_count', function($data){
                return [ $data['id'], $data['plans_count'] ];
            })
            ->addColumn('status', function ($data) {
                return [ $data['status'], $data['deleted_at'] ];
            })
            ->addColumn('action', function ($data) {
                return [ $data['id'], $data['status'], $data['deleted_at'] ];
            })
            ->make(true);
        }   
        return view('admin.category.list', compact('page_title', 'page_description', 'user', 'inputs'));
    }

    public function view($id, Request $request){
        $user = Auth::guard('admin')->user();
        $page_title = 'Category Details';
        $page_description = 'Category Details';
        if($request->ajax()){
            $start = 0;
            
            if($request->get('status') == 2){
                $query = Plans::onlyTrashed();
            }else{
                 $query = Plans::where('category_id',$id);
                if($status = $request->get('status')){
                    $query = $query->where('status', 'LIKE', $status)->where('user_id', '=', '');
                }
            }
        
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($data) use ($id) {
                return [ $data['id'], $data['status'], $id, $data['deleted_at'] ];
            })
            ->make(true);
        }   

        $category = PlanCategories::find($id);
        return view('admin.category.view', compact('page_title', 'page_description', 'user', 'category'));
    }

    public function add(){
        $user = Auth::guard('admin')->user();
        $page_title = 'Add Category';
        $page_description = 'Some description for the page';
        return view('admin.category.add', compact('page_title', 'page_description', 'user'));

    }
    
    public function edit($id){
        $user = Auth::guard('admin')->user();
        $category = PlanCategories::find($id);
        $page_title = 'Edit Category';
        $page_description = 'Edit Category';

        return view('admin.category.add', compact('page_title', 'page_description', 'user', 'category'));
    }

    public function save(Request $request){
        $inputs = $request->all();
        $id = $request->get('id');
        // var_dump()
        $validator = Validator::make($inputs, [
            'name' => $id ? 'required|unique:plan_categories,name,'.$id.',id' : 'required|unique:plan_categories,name' ,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if($id){
            $category = PlanCategories::find($id);
        }else{
            $category = new PlanCategories();
        }

        $category->name = $inputs['name'];
        $category->slug = str_replace(' ', '-', $inputs['name']);
        $category->status = @$inputs['status'] ? : PlanCategories::STATUS_ACTIVE ;
        
        if($category->save()){

            $request->session()->flash('success', 'Plan Category '. (isset($request->user_id) ? 'updated' : 'added') .' successfully.');

        }

        return redirect()->intended('admin/categories');

    } 



    public function delete($id){
        $category = PlanCategories::where('id', $id)->first();
        if(empty($category)){
            return response()->json(['error'=>"Plan category is not available to delete."]);
        }

        $category->delete();
        return response()->json(['success'=>"Plan category deleted successfully."]);

    }

    public function restore($id){
        PlanCategories::withTrashed()->find($id)->restore();
        return response()->json(['success' => 'Plan category restored successfully']);
    }

    public function addPlan($cat_id){

        $user = Auth::guard('admin')->user();
        $page_title = 'Add Plan';
        $page_description = 'Some description for the page';
        $category = PlanCategories::find($cat_id);

        return view('admin.category.plan.add', compact('page_title', 'page_description', 'user', 'category'));
    }

    public function editPlan($cat_id, $id){

        $user = Auth::guard('admin')->user();
        $page_title = 'Edit Plan';
        $page_description = 'Some description for the page';

        $category = PlanCategories::find($cat_id);
        $plan = Plans::where('id', $id)->first();

        return view('admin.category.plan.add', compact('page_title', 'page_description', 'user', 'category', 'plan'));
    }

    public function savePlan($cat_id, Request $request){

        $inputs = $request->all();

        $id = $request->get('id');
        $validator = Validator::make($inputs, [
            // 'name' => $id ? 'required|unique:plans,name,'.$id.',id' : 'required|unique:plan_categories,name' ,
            'name' =>  $id ? 'required|unique:plans,name,'.$id.',id,category_id,' . $cat_id : 'required|unique:plans,name,NULL,id,category_id,' . $cat_id,
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if($id){
            $plan = Plans::where('id', $id)->first();
        }else{
            $plan = new Plans();
        }
        $plan->category_id = $cat_id;
        $plan->name = @$inputs['name'];
        $plan->description = @$inputs['description'];
        $plan->commission = @$inputs['commission'];
        $plan->status = @$inputs['status'] ? : Plans::STATUS_ACTIVE;
        if($plan->save()){

            if(!@$inputs['id']){

                $users = User::where('type', 2)->get();

                foreach ($users as $key => $user) {
                    $email = $user['email'];
                    
                    dispatch(new AddPlanCatJob($email,$user));
                    
                    /*\Mail::send('email.planadd', $confirmed = array('user_info'=>$user), function($message ) use ($email){
                        $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                        ->subject('New plan introduction')
                        ->to($email);
                    });*/
                }
            }

            $request->session()->flash('success', 'Plan '. (isset($request->user_id) ? 'updated' : 'added') .' successfully.');

        }
        return redirect()->intended('admin/plan-category/'.$cat_id);
    }

    public function deletePlan($id){
        $plan = Plans::where('id', $id)->first();
        if(empty($plan)){
            return response()->json(['error'=>"Plan is not available to delete."]);
        }

        $plan->delete();
        return response()->json(['success'=>"Plan deleted successfully."]);

    }

    public function restorePlan($id){
        Plans::withTrashed()->find($id)->restore();
        return response()->json(['success'=>"Plan restored successfully."]);

    }

    public function manageCategories(Request $request)
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Categories';
        $page_description = 'Some description for the page';

        $page_title = 'Manage Categories'  ;
        $page_description = 'Manage Categories';
        $start = 0;

        if($request->get('status') == 2){
            $query = Categories::onlyTrashed();
        }else{
            $query = Categories::latest();
            if($status = $request->get('status')){
                $query = $query->where('status', 'LIKE', $status);
            }
        }        
        
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($data) {
                return [ $data['status'], $data['deleted_at']];
            })
            ->addColumn('action', function ($data) {
                return [ $data['id'], $data['status'], $data['deleted_at']];
            })
            ->addColumn('image', function ($data) {
                if ($data->image) {
                    return '<img src="'. Helpers::asset('uploads/category/'.$data->image ) .'" width="100px">';
                }
                return '<img src="'. Helpers::asset('/images/No-image-icon.png') .'" width="100px">';
            })
            ->rawColumns(['image'])
            ->make(true);
        }   
        return view('admin.manage-category.list', compact('page_title', 'page_description', 'user', 'inputs'));
    }

    public function addManageCategory(){
        $user = Auth::guard('admin')->user();
        $page_title = 'Add Category';
        $page_description = 'Some description for the page';
        $platforms = SocialPlatform::where('status','1')->get();
        return view('admin.manage-category.add', compact('page_title', 'page_description', 'user','platforms'));

    }

    public function saveManageCategory(Request $request){
        $inputs = $request->all();
        $platforms  = $request->platform_id;
        $id = $request->get('id');       
        if($id){
            $category = Categories::find($id);
        }else{
            $category = new Categories();
        }


        $uploadedfile = $request->file('image');
        if ($request->has('image') && !empty($request->file('image'))) {
            $directory = 'uploads/category';
            if (! File::exists(public_path().'/'.$directory.'/')) {
                File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
            }
            $filename = preg_replace('/\..+$/', '', $uploadedfile->getClientOriginalName()).time().'.'.$uploadedfile->getClientOriginalExtension();
            $destinationPath = public_path($directory);
            $uploadedfile->move($destinationPath, $filename);
            $category->image = $filename ;
        }

       
        $category->name = $inputs['name'];
        $category->slug = str_replace(' ', '-', $inputs['name']);
        $category->status = @$inputs['status'] ? : Categories::STATUS_ACTIVE ;        
        if($category->save()){
            $category_id=@$category->id;
             $data = array();
                if(empty($id)){
                    if(!empty($platforms)){
                     foreach($platforms as $platform) {
                     $data[] = $platform;
                     $row=new SocialPlatformCategory();
                     $row->platform_id=$platform;
                     $row->category_id=$category_id;
                     $row->save();
                    }
                    }
                    
             }

            $request->session()->flash('success', 'Category '. (isset($request->user_id) ? 'updated' : 'added') .' successfully.');
        }
       

        return redirect()->intended('admin/manage-categories');

    }

    public function editManageCategory($id){
        $user = Auth::guard('admin')->user();
        $category = Categories::find($id);
        $platforms = SocialPlatform::where('status','1')->get();
        $page_title = 'Edit ' .$category['name'];
        $page_description = 'Some description for the page';
        return view('admin.manage-category.add', compact('page_title', 'page_description', 'user', 'category','platforms'));
    }

    public function deleteManageCategory($id){
        $category = Categories::where('id', $id)->first();
        if(empty($category)){
            return response()->json(['error'=>"Category is not available to delete."]);
        }

        $category->delete();
        return response()->json(['success'=>"Category deleted successfully."]);

    }
    public function restoreManageCategory($id){
        Categories::withTrashed()->find($id)->restore();
        return response()->json(['success' => 'Category restored successfully']);
    }
    
}
