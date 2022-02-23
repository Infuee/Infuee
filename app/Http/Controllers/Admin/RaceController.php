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
use App\Models\Race;
use App\Models\SocialPlatformCategory;
use DB;
use File;
use Helpers;
use App\Mail\AddPlanCatSendEmail;
use App\Jobs\AddPlanCatJob;
use Mail;

class RaceController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Race';
        $page_description = 'Mange Race  List';

        $start = 0;

        if($request->get('status') == 2){
            $query = Race::onlyTrashed(); 
        }else{
            $query = Race::get();        
            if($status = $request->get('status')){
                $query = $query->where('status', 'LIKE', $status);
            }
        }
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('status', function ($data) {
                return [ $data['status'], $data['deleted_at'] ];
            })
            ->addColumn('status', function ($data) {
                return '<span style="width: 137px;">
                        <span class="label font-weight-bold label-lg label-light-'. ( $data['status'] == 1 ? 'success' : 'danger' ) .' label-inline">'. ( $data['status'] == 1 ? 'Active' : 'Archived' ) .'</span>
                    </span>' ;
            })
            ->addColumn('action', function ($data){

                if($data['deleted_at']){
                    $html =  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="Job" url="/admin/restore-manage-race/' . $data['id'] .'">
                        <span class="svg-icon svg-icon-md">
                            <i class="fa fa-redo"></i>
                        </span>
                    </a>';
                }else{
                    $html = '<a href="'. (url( '/admin/edit-manage-race/' . $data['id'] )  )  .'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit Race">
                        <span class="svg-icon svg-icon-md"><i class="fa fa-edit"></i>
                        </span>
                    </a>';
                
                    $html .=  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Job" url="/admin/delete-race/' . $data['id'] .'">
                        <span class="svg-icon svg-icon-md">
                            <i class="fa fa-trash"></i>
                        </span>
                    </a>';
                }

                return $html ;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
        }   
        return view('admin.race.list', compact('page_title', 'page_description', 'user', 'inputs'));
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
        $page_title = 'Mange Race';
        $page_description = 'Mange Race  List';
        return view('admin.race.add', compact('page_title', 'page_description', 'user'));

    }
    
    public function edit($id){
        $user = Auth::guard('admin')->user();
        $race = Race::find($id);
        $page_title = 'Mange Race';
        $page_description = 'Mange Race  List';

        return view('admin.race.add', compact('page_title', 'page_description', 'user', 'race'));
    }

    public function save(Request $request){
        $inputs = $request->all();
        $id = $request->get('id');
        $validator = Validator::make($inputs, [
            'title' => @$id ? 'required|unique:race,title,'.$id.',id' : 'required|unique:race,title' ,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if($id){
            $category = Race::find($id);
        }else{
            $category = new Race();
        }

        $category->title = $inputs['title'];
        $category->status = @$inputs['status'] ? : Race::STATUS_ACTIVE ;
        
        if($category->save()){

            $request->session()->flash('success', 'Race '. (isset($request->id) ? 'updated' : 'added') .' successfully.');

        }

        return redirect()->intended('admin/race');

    } 



    public function delete($id){
        $race = Race::where('id', $id)->first();
        if(empty($race)){
            return response()->json(['error'=>"Race is not available to delete."]);
        }

        $race->delete();
        return response()->json(['success'=>"Race deleted successfully."]);

    }

    public function restore($id){
        Race::withTrashed()->find($id)->restore();
        return response()->json(['success' => 'Race restored successfully']);
    }
}
