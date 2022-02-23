<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;
use App\Models\Campaign;
use App\User;
use App\Models\SocialPlatformCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $inputs = $request->all();

        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Campaigns';
        $page_description = 'Mange Campaign';

        $start = 0;
 
        $query = Campaign::withCount(['jobs'])->with(['users']);
        if($request->get('status') == 2){
            $query = $query->onlyTrashed(); 
        }else{
            if($status = $request->get('status')){
                $query = $query->where('status', 'LIKE', $status);
            }
        }

        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return [ $data['id'], $data['status'], $data['deleted_at'] ];
            })
            ->addColumn('status', function ($data) {
                $class = $data['status'] == 1 ? 'success' : 'danger';
                $status = $data['status'] == 1 ? 'Active' : 'Inactive';

                return '<span style="width: 137px;">
                    <span class="label font-weight-bold label-lg label-light-'.$class.' label-inline">'.$status.'</span>
                </span>';


                return [ $data['status'], $data['deleted_at'] ];
            })
           
            ->addColumn('created_at', function ($data) {
                if(empty($data->created_at)){
                    return 'NA';
                }
               return [ date('d F Y', strtotime($data->created_at)) ];
            })
            ->addColumn('jobs_count', function ($data) {
               
               return [ $data['id'], $data['jobs_count'] ];
            })
            
            ->addColumn('user_name', function ($data) {
                

                return [ $data['id'], $data['created_by'],isset($data->users['first_name'])?$data->users['first_name']:"" ];
            })
           

            ->addColumn('image', function ($data) {
                if(!empty($data->image)){

                return '<img src="'. asset('uploads/compaign/'.$data->image) .'" width="100px">';
                }else{
                   return '<img src="'. asset('uploads/compaign/demo.png') .'" width="100px">';
                }
            })

            ->rawColumns(['image','status'])
            ->make(true);
        }   
        return view('admin.campaign.list', compact('page_title', 'page_description', 'user', 'inputs'));
    }


    public function add(){
        $user = Auth::guard('admin')->user();
        $page_title = 'Add Campaign';
        $page_description = 'Some description for the page';
        $users=User::where('type',User::TYPE_USER)->orderBy('first_name')->get();
        $influencers=User::where('type',User::TYPE_INFLUENCER)->orderBy('first_name')->get();
        return view('admin.campaign.add', compact('page_title', 'page_description', 'user','users','influencers'));

    }
    
    public function edit($id){
        $user = Auth::guard('admin')->user();
        $campaign = Campaign::find($id);
        $page_title = 'Edit Campaign';
        $page_description = 'Edit Campaign';
        $users=User::where('type',User::TYPE_USER)->orderBy('first_name')->get();
        $influencers=User::where('type',User::TYPE_INFLUENCER)->orderBy('first_name')->get();
        return view('admin.campaign.add', compact('page_title', 'page_description', 'user', 'campaign','users','influencers'));
    }

    public function save(Request $request){
        $inputs = $request->all();
        $id = $request->get('id');
        
        $validator = Validator::make($inputs, [
            'title' => 'required',
            "description"=> "required",
            "location"=> "required",
            "image" => "nullable|max:2048"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if($id){
            $campaign = Campaign::find($id);
        }else{
            $campaign = new Campaign();
        }
        $campaign->title = $inputs['title'];
        $campaign->slug = $campaign->getSlugs($inputs['title']);
        $campaign->status = @$inputs['status'] ? : Campaign::STATUS_ACTIVE ;
        $campaign->description = $inputs['description'];
        $campaign->location = $inputs['location'];
        $campaign->lat = $inputs['lat'];
        $campaign->lng = $inputs['lng'];
        $campaign->website = $inputs['website'];
        $campaign->created_by = $inputs['created_by'];
        
        $uploadedfile = $request->file('image');
        if ($request->has('image') && !empty($request->file('image'))) {
            $directory = 'uploads/compaign';
            if (! File::exists(public_path().'/'.$directory.'/')) {
                File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
            }
            $filename = preg_replace('/\..+$/', '', $uploadedfile->getClientOriginalName()).time().'.'.$uploadedfile->getClientOriginalExtension();
            $destinationPath = public_path($directory);
            $uploadedfile->move($destinationPath, $filename);
            $campaign->image = $filename ;
        }
        
        if($campaign->save()){
            $request->session()->flash('success', 'Campaign '. ($id ? 'updated' : 'added') .' successfully.');
        }

        return redirect()->intended('admin/campaigns');

    } 



    public function delete($id){
        $platform = Campaign::where('id', $id)->first();
        if(empty($platform)){
            return response()->json(['error'=>"Campaign is not available to delete."]);
        }

        $platform->delete();
        return response()->json(['success'=>"Campaign deleted successfully."]);

    }

    public function restore($id){
        Campaign::withTrashed()->find($id)->restore();
        return response()->json(['success' => 'Campaign restored successfully']);
    }
       
}
