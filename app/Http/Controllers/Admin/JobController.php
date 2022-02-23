<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Categories;
use App\Models\Race;
use App\Models\Job;
use App\Models\JobHiring;
use App\Models\SocialPlatform;
use App\Models\JobPlatform;
use App\Models\Campaign;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class JobController extends Controller
{
    public function index(Request $request, $campaign_id = false)
    {
        $inputs = $request->all();
          
        $user = Auth::guard('admin')->user();
        $page_title = 'Mange Jobs';
        $page_description = 'Mange Jobs';

        if($request->ajax()){
            $start = 0;
            
            $query = Job::with(['category']);
            if($request->get('status') == 2){
                $query = $query->onlyTrashed();
            }else{
                if($status = $request->get('status')){
                    $query = $query->where('status', 'LIKE', $status);
                }
            }
            if(@$campaign_id){
                $query = $query->where('campaign_id', $campaign_id );
            }else{
                $query = $query->whereNull('campaign_id');
            }

            return datatables()->of($query)
            ->addIndexColumn()
            
            ->addColumn('categories', function($data){
                return $data->categoriesSpan();
            })
            ->addColumn('price', function($data){
                return env('CURRENCY').$data->price;
            })
            ->addColumn('influencers', function($data){
                if($data->campaign_id){

                return $data->influencers;
                }
            })
            ->addColumn('duration', function($data){
                return $data->getMinutes(). ' Minutes, '. $data->getSeconds(). ' Seconds';
            })
            ->addColumn('user_name', function ($data) {

                return [ $data['id'], $data['created_by'],isset($data->users['first_name'])?$data->users['first_name']:"" ,$data['campaign_id']];

            })
            ->addColumn('created_at', function ($data) {
                if(empty($data->created_at)){
                    return 'NA';
                }
               return [ date('d F Y', strtotime($data->created_at)) ];
            })
            ->addColumn('status', function ($data) {
                return '<span style="width: 137px;">
                        <span class="label font-weight-bold label-lg label-light-'. ( $data['status'] == 1 ? 'success' : 'danger' ) .' label-inline">'. ( $data['status'] == 1 ? 'Active' : 'Archived' ) .'</span>
                    </span>' ;
            })
            ->addColumn('action', function ($data) use ($campaign_id) {

                if($data['deleted_at']){
                    $html =  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon restore" title="Restore" data-type="Job" url="/admin/job/restore/' . $data['id'] .'">
                        <span class="svg-icon svg-icon-md">
                            <i class="fa fa-redo"></i>
                        </span>
                    </a>';
                }else{
                    $html = '<a href="'. ( $campaign_id ? url( '/admin/campaign/'. $campaign_id .'/job/edit/' . $data['id'])  : url( '/admin/job/edit/' . $data['id'] )  )  .'" class="btn btn-sm btn-clean btn-icon mr-2" title="Edit">
                        <span class="svg-icon svg-icon-md"><i class="fa fa-edit"></i>
                        </span>
                    </a>';
                
                    $html .=  '<a href="javascript:;" class="btn btn-sm btn-clean btn-icon delete" title="Delete" data-type="Job" url="/admin/job/delete/' . $data['id'] .'">
                        <span class="svg-icon svg-icon-md">
                            <i class="fa fa-trash"></i>
                        </span>
                    </a>';
                }

                return $html ;
            })
            ->addColumn('image', function ($data) {
                return '<img src="'. asset('uploads/job/'.$data->image) .'" width="100px">';
            })
            ->rawColumns(['categories', 'image', 'action', 'status'])
            ->make(true);
        }   
        return view('admin.job.list', compact('page_title', 'page_description', 'user', 'inputs', 'campaign_id'));
    }


    public function add($campaign_id = false){
         $user = Auth::guard('admin')->user();
        $page_title = 'Add Job';
        $page_description = 'Some description for the page';
        $categories = Categories::where('status','1')->get();
        $race = race::where('status','1')->get();
        $users=User::where('type',User::TYPE_USER)->orderBy('first_name')->get();
        $influencers=User::where('type',User::TYPE_INFLUENCER)->orderBy('first_name')->get();
        $platforms = SocialPlatform::where('status','1')->get();
        return view('admin.job.add', compact('page_title', 'page_description', 'user', 'categories', 'platforms', 'campaign_id','users','influencers','race'));

    }
    
    public function edit($campaign_id = false, $id = false ){
        $user = Auth::guard('admin')->user();
        $id = !$id ? $campaign_id : $id ;
        if(\Request::segment(3) == 'edit') {
           $campaign_id = false; 
        }
        $job = Job::find($id);
        $users=User::where('type',User::TYPE_USER)->orderBy('first_name')->get();
        $influencers=User::where('type',User::TYPE_INFLUENCER)->orderBy('first_name')->get();
        $minimum_followers =json_decode($job->minimum_followers,true);
        $race_id ='0';
        $race_id =json_decode(@$job->race_id,true);
        $page_title = 'Edit Job';
        $page_description = 'Edit Job';
        $categories = Categories::where('status','1')->get();
        $race = race::where('status','1')->get();
        $platforms = SocialPlatform::where('status','1')->get();
        $hirejob=JobHiring::where(['job_id' => $job->id, 'user_id' => auth()->id() ])->first();
        return view('admin.job.add', compact('page_title', 'page_description', 'user', 'job', 'categories', 'platforms', 'campaign_id','minimum_followers','users','influencers','race','race_id','hirejob'));
    }

    public function save(Request $request, $campaign_id = false){
        $inputs = $request->all();
        $id = $request->get('id');
        $min_age = floatval(str_replace(',' ,'', $request->input('min_age')));
        $max_age = floatval(str_replace(',' ,'', $request->input('max_age')));
        //Get your range
        $min = $min_age  + 0.01;
        $max = $max_age - 0.01;
        $validator = Validator::make($inputs, [
            'title' => 'required',
            "description"=> "required",
            "caption"=> "required",
            "platforms" => "required|array|min:1",
            "category" =>  "required",
            //"platforms" =>  "required",
            //"influencers" => "required",
            "minutes" => "required_if:seconds,==,NULL",
            "seconds" => "required_if:minutes,==,NULL",
            //"promo_days" => "required",
            //"price" => "required"
            "image_video" => "nullable|max:2048",
            "image" => "nullable|max:2048",
            'min_age' => [
            'nullable',
            function($attribute, $value, $fail) use($min_age, $max) {
                    if ($min_age < 0 ||  $min_age > $max) {
                        return $fail(' Min age must be between 0 and max age');
                    }
                }],
            'max_age' => [
            'nullable',
            function($attribute, $value, $fail) use($max_age, $min) {
                    if ($max_age < $min) {
                        return $fail(' Max age must be greater than min age.');
                    }
                }] 
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        if($id){
            $job = Job::find($id);
        }else{
            $job = new Job();
        }
        $job_campaign_id=Job::where('campaign_id',$campaign_id)->first();
         if(!empty($job_campaign_id->campaign_id)){
            $campaign_id;
         }else{
            $campaign_id=$campaign_id;
         }
         if($campaign_id){
           $campaig_created_by=Campaign::where('id',$campaign_id)->first();
           $inputs['created_by']=@$campaig_created_by->created_by;
         }

        $job->campaign_id = $campaign_id ? : null;
        $job->title = $inputs['title'];
        $job->slug = $job->getSlugs($inputs['title']);
        $job->status = @$inputs['status'] ? : Job::STATUS_ACTIVE ;
        $job->description = $inputs['description'];
        $job->duration = ((int) $inputs['minutes'] * 60 )  + (int) @$inputs['seconds'];
        $job->category_id = $inputs['category'];
        $job->race_id = json_encode(@$inputs['race_id']);
        $job->influencers = @$inputs['influencers'] ? : 'null';
        $job->promo_days = @$inputs['promo_days'] ? : 'null';
        $job->price = $inputs['price'];
        $job->location = $inputs['location'];
        $job->min_age = @$inputs['min_age'];
        $job->max_age = @$inputs['max_age'];
        $job->lat = @$inputs['lat'];
        $job->lng = @$inputs['lng'];
        $job->caption = @$inputs['caption'];
        $job->radius = @$inputs['radius'];
        $job->created_by = @$inputs['created_by'];
        $job->minimum_followers = json_encode(@$inputs['minimum_followers']);
        
        $uploadedvideoimagefile = $request->file('image_video');
        $uploadedfile = $request->file('image');
        if ($request->has('image') && !empty($request->file('image'))) {
            $directory = 'uploads/job';
            if (! File::exists(public_path().'/'.$directory.'/')) {
                File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
            }
            $filename = preg_replace('/\..+$/', '', $uploadedfile->getClientOriginalName()).time().'.'.$uploadedfile->getClientOriginalExtension();
            $destinationPath = public_path($directory);
            $uploadedfile->move($destinationPath, $filename);
            $job->image = $filename ;
        }
        if ($request->has('image_video') && !empty($request->file('image_video'))) {
            $directory = 'uploads/job';
            if (! File::exists(public_path().'/'.$directory.'/')) {
                File::makeDirectory(public_path().'/'.$directory.'/',0755,true);
            }
            $filename = preg_replace('/\..+$/', '', $uploadedvideoimagefile->getClientOriginalName()).time().'.'.$uploadedvideoimagefile->getClientOriginalExtension();
            $destinationPath = public_path($directory);
            $uploadedvideoimagefile->move($destinationPath, $filename);
            $job->image_video = $filename ;
        }
        if($job->save()){
            JobPlatform::where('job_id', $job->id)->delete();
            foreach($inputs['platforms'] as $platform){
                JobPlatform::create(['platform_id'=>$platform, 'job_id' => $job->id]);
            }
            $request->session()->flash('success', 'Job '. ($id ? 'updated' : 'added') .' successfully.');
        }

        if($campaign_id){
            return redirect()->intended('admin/campaign/'. $campaign_id .'/jobs');
        }

        return redirect()->intended('admin/jobs');

    } 

    public function delete($id){
        $platform = Job::where('id', $id)->first();
        if(empty($platform)){
            return response()->json(['error'=>"Job is not available to delete."]);
        }

        $platform->delete();
        return response()->json(['success'=>"Job deleted successfully."]);

    }

    public function restore($id){
        Job::withTrashed()->find($id)->restore();
        return response()->json(['success' => 'Job restored successfully']);
    }
       
}
