<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\InfluencerRequests;
use App\Models\Categories;
use App\Models\SocialPlatform;
use App\Models\SocialPlatformCategory;
use App\Models\Plans;
use Illuminate\Support\Facades\DB;

class SocialPlatformController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Social Platforms';
        $page_description = 'Mange Social Platforms';

        $start = 0;

        if($request->get('status') == 2){
            $query = SocialPlatform::onlyTrashed()->with(['categories', 'categories.n']); 
        }else{
            $query = SocialPlatform::with(['categories', 'categories.category']);
            if($status = $request->get('status')){
                $query = $query->where('status', 'LIKE', $status);
            }
        }
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('categories', function($data){
                return $data->categoriesSpan();
            })
            ->addColumn('status', function ($data) {
                return [ $data['status'], $data['deleted_at'] ];
            })
            ->addColumn('action', function ($data) {
                return [ $data['id'], $data['status'], $data['deleted_at'] ];
            })
            ->rawColumns(['categories'])
            ->make(true);
        }   
        return view('admin.social_platforms.list', compact('page_title', 'page_description', 'user', 'inputs'));
    }


    public function add(){

        $user = Auth::guard('admin')->user();
        $page_title = 'Add Social Platform';
        $page_description = 'Some description for the page';
        $categories = Categories::where('status','1')->get();
        return view('admin.social_platforms.add', compact('page_title', 'page_description', 'user', 'categories'));

    }
    
    public function edit($id){
        $user = Auth::guard('admin')->user();
        $platform = SocialPlatform::find($id);
        $page_title = 'Edit Social Platform';
        $page_description = 'Edit Social Platform';
        $categories = Categories::where('status','1')->get();
        return view('admin.social_platforms.add', compact('page_title', 'page_description', 'user', 'platform', 'categories'));
    }

    public function save(Request $request){
        $inputs = $request->all();
        $id = $request->get('id');
        
        $validator = Validator::make($inputs, [
            'name' => $id ? 'required|unique:social_platform,name,'.$id.',id' : 'required|unique:social_platform,name',
            "categories"    => "required|array|min:1",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if($id){
            $platform = SocialPlatform::find($id);
        }else{
            $platform = new SocialPlatform();
        }

        $platform->name = $inputs['name'];
        $platform->slug = str_replace(' ', '-', $inputs['name']);
        $platform->status = @$inputs['status'] ? : SocialPlatform::STATUS_ACTIVE ;
        
        if($platform->save()){
            SocialPlatformCategory::where('platform_id', $platform->id)->delete();
            foreach($inputs['categories'] as $categorie){
                SocialPlatformCategory::create(['platform_id'=> $platform->id, 'category_id' => $categorie]);
            }
            $request->session()->flash('success', 'Social Platform '. ($id ? 'updated' : 'added') .' successfully.');
        }

        return redirect()->intended('admin/social-platforms');

    } 



    public function delete($id){
        $platform = SocialPlatform::where('id', $id)->first();
        if(empty($platform)){
            return response()->json(['error'=>"Social Platform is not available to delete."]);
        }

        $platform->delete();
        return response()->json(['success'=>"Social Platform deleted successfully."]);

    }

    public function restore($id){
        SocialPlatform::withTrashed()->find($id)->restore();
        return response()->json(['success' => 'Social Platform restored successfully']);
    }
       
}
