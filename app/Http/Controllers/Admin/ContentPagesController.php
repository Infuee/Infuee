<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Admin;
use App\Models\InfluencerRequests;
use App\Models\ContentPages;
use App\Models\Faq;
use App\Models\FaqCat;
use App\Models\ContactUs;
use App\User;
use DB;

class ContentPagesController extends Controller
{
    public function index(Request $request)
    {
        
    }

    public function view($id){        

    }

    public function howItWorks(Request $request){        
        // echo $content->id;die;
        if($request->post('editor') != ''){
            $content = ContentPages::select(['how_it_works','id'])->first()->toArray();
            if(!empty($content)){
                $id = $content['id'];
                $ContentPages = ContentPages::where('id',$id)->first();
            }else{            
                $ContentPages = new ContentPages();
            }
            $ContentPages->how_it_works = $request->post('editor');
            $ContentPages->save();
        }
        $content = ContentPages::select(['how_it_works'])->first();
        $user = Auth::guard('admin')->user();
        $page_title = 'How It Works'  ;
        $page_description = 'How It Works';
        $page = 'view';
        return view('admin.content-pages.view', compact('page_title', 'page_description', 'user', 'page','content'));
    }
    public function homePageTopSection(Request $request){
        // echo $content->id;die;
        if($request->post('editor') != ''){
            $content = ContentPages::select(['home_page_top_section','id'])->first()->toArray();
            if(!empty($content)){
                $id = $content['id'];
                $ContentPages = ContentPages::where('id',$id)->first();
            }else{            
                $ContentPages = new ContentPages();
            }
            $ContentPages->home_page_top_section = $request->post('editor');
            $ContentPages->save();
        }
        $content = ContentPages::select(['home_page_top_section'])->first();
        $user = Auth::guard('admin')->user();
        $page_title = 'Home Page Top Section'  ;
        $page_description = 'Home Page Top Section';
        $page = 'view';
        return view('admin.content-pages.home_page_top_section', compact('page_title', 'page_description', 'user', 'page','content'));
    }
    public function homePageMiddleSection(Request $request){
        // echo $content->id;die;
        if($request->post('editor') != ''){
            $content = ContentPages::select(['home_page_middle_section','id'])->first()->toArray();
            if(!empty($content)){
                $id = $content['id'];
                $ContentPages = ContentPages::where('id',$id)->first();
            }else{            
                $ContentPages = new ContentPages();
            }
            $ContentPages->home_page_middle_section = $request->post('editor');
            $ContentPages->save();
        }
        $content = ContentPages::select(['home_page_middle_section'])->first();
        $user = Auth::guard('admin')->user();
        $page_title = 'Home Page Middle Section'  ;
        $page_description = 'Home Page Middle Section';
        $page = 'view';
        return view('admin.content-pages.home_page_middle_section', compact('page_title', 'page_description', 'user', 'page','content'));
    }

    public function homePage(Request $request){        
        // echo $content->id;die;
        if($request->post('editor') != ''){
            $content = ContentPages::select(['home_page','id'])->first()->toArray();
            if(!empty($content)){
                $id = $content['id'];
                $ContentPages = ContentPages::where('id',$id)->first();
            }else{            
                $ContentPages = new ContentPages();
            }
            $ContentPages->home_page = $request->post('editor');
            $ContentPages->save();
        }
        $content = ContentPages::select(['home_page'])->first();
        $user = Auth::guard('admin')->user();
        $page_title = 'Home page'  ;
        $page_description = 'Home page';
        $page = 'view';
        return view('admin.content-pages.home_page', compact('page_title', 'page_description', 'user', 'page','content'));
    }

    public function termsOfService(Request $request){        
        // echo $content->id;die;
        if($request->post('editor') != ''){
            $content = ContentPages::select(['terms_of_service','id'])->first();
            if(!empty($content)){
                $data = $content->toArray();
                $id = $data['id'];
                $ContentPages = ContentPages::where('id',$id)->first();
            }else{            
                $ContentPages = new ContentPages();
            }
            $ContentPages->terms_of_service = $request->post('editor');
            $ContentPages->save();
        }
        $content = ContentPages::select(['terms_of_service'])->first();
        $user = Auth::guard('admin')->user();
        $page_title = 'Terms Of Service'  ;
        $page_description = 'Terms Of Service';
        $page = 'view';
        return view('admin.content-pages.terms_of_service', compact('page_title', 'page_description', 'user', 'page','content'));
    }

    public function privacyPolicy(Request $request){        
        // echo $content->id;die;
        if($request->post('editor') != ''){
            $content = ContentPages::select(['privacy_policy','id'])->first();
            if($content){
                $data = $content->toArray();
                $id = $data['id'];
                $ContentPages = ContentPages::where('id',$id)->first();
            }else{            
                $ContentPages = new ContentPages();
            }
            $ContentPages->privacy_policy = $request->post('editor');
            $ContentPages->save();
        }
        $content = ContentPages::select(['privacy_policy'])->first();
        $user = Auth::guard('admin')->user();
        $page_title = 'Privacy Policy'  ;
        $page_description = 'Privacy Policy';
        $page = 'view';
        return view('admin.content-pages.privacy_policy', compact('page_title', 'page_description', 'user', 'page','content'));
    }

    public function userAgreement(Request $request){        
        // echo $content->id;die;
        if($request->post('editor') != ''){
            $content = ContentPages::select(['user_agreement','id'])->first()->toArray();
            if(!empty($content)){
                $id = $content['id'];
                $ContentPages = ContentPages::where('id',$id)->first();
            }else{            
                $ContentPages = new ContentPages();
            }
            $ContentPages->user_agreement = $request->post('editor');
            $ContentPages->save();
        }
        $content = ContentPages::select(['user_agreement'])->first();
        $user = Auth::guard('admin')->user();
        $page_title = 'Privacy Policy'  ;
        $page_description = 'Privacy Policy';
        $page = 'view';
        return view('admin.content-pages.user_agreement', compact('page_title', 'page_description', 'user', 'page','content'));
    }

    public function faqList(Request $request,$id=''){
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title ='Faq';
        $page_description = 'Faq';
        $start = 0;
        DB::statement(DB::raw('set @rownum='.$start));
        if($request->get('status') == 2){
            // $query = Faq::onlyTrashed()->select([DB::raw('@rownum := @rownum + 1 AS rank'), 'id','question','answer','deleted_at']);
            $query = Faq::onlyTrashed();
        }else{
            // $query = Faq::select([DB::raw('@rownum := @rownum + 1 AS rank'), 'id','question','answer','deleted_at']);
            $query = Faq::all();
            if($status = $request->get('status')){
                $query = $query->where('status', 'LIKE', $status);
            }
        }

        if(!empty($id)){
            @$query = $query->where('cat_id',$id);
        }
        
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($user) {
                return [$user->id, $user->status,$user->deleted_at];
            })
            ->make(true);
        }  

        return view('admin.content-pages.faq_list', compact('page_title', 'page_description','user','id','inputs'));
    }

    public function faqCatList(Request $request){
        $inputs = $request->all();
        $user = Auth::guard('admin')->user();
        $page_title ='Faq'  ;
        $page_description = 'Faq';
        $start = 0;
        if($request->get('status') == 2){
            $query = FaqCat::onlyTrashed()->withCount('faqs');
        }else{
            $query = FaqCat::withCount('faqs');
        }
        
        // echo '<pre>';print($query); echo '</pre>';die;
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('faqs_count', function ($data) {
                return [ $data['id'], $data['faqs_count']];
            })
            ->addColumn('action', function ($data) {
                return [ $data['id'],$data['status'],$data['deleted_at']];
            })
            ->make(true);
        }  

        return view('admin.content-pages.faq_cat_list', compact('page_title', 'page_description','user','inputs'));
    }


    public function addFaq(Request $request,$id=''){
        if(!empty($request->post('question'))){
            if(!empty($id)){
                $Faq = Faq::where('id',$id)->first();
            }else{
                $Faq = new Faq();
            }
            $Faq->question = $request->post('question');
            $Faq->answer = $request->post('answer');
            $Faq->cat_id = $request->post('cat_id');
            $Faq->save();
            return redirect('admin/faq-list');
        }
        if(!empty($id)){
            $FaqData = Faq::where('id',$id)->first();
            $faq_cat = FaqCat::where('status', 1)->get();
            $user = Auth::guard('admin')->user();
            $page_title = 'Add Faq'  ;
            $page_description = 'Add Faq';
            $page = 'view';
            return view('admin.content-pages.add_faq', compact('page_title', 'page_description','user','page','FaqData','faq_cat','id'));
        }
        $faq_cat = FaqCat::where('status', 1)->get();
        $user = Auth::guard('admin')->user();
        $page_title = 'Add Faq'  ;
        $page_description = 'Add Faq';
        $page = 'view';
        return view('admin.content-pages.add_faq', compact('page_title', 'page_description','user','page','faq_cat','id'));
    }

    public function addFaqCat(Request $request,$id=''){ 
        if(!empty($request->post('cat_name'))){
            if(!empty($id)){
                $Category = FaqCat::where('id',$id)->first();
            }else{
                $Category = new FaqCat();
            }            
            $Category->cat_name = $request->post('cat_name');
            $Category->save();
            return redirect('admin/faq-cat-list');
        }
        if(!empty($id)){
            $CatData = FaqCat::where('id',$id)->first();
            $user = Auth::guard('admin')->user();
            $page_title ='Add Faq Category'  ;
            $page_description = 'Add Faq Category';
            return view('admin.content-pages.add_faq_cat', compact('page_title', 'page_description','user','CatData','id'));
            } 
            $user = Auth::guard('admin')->user();
            $page_title ='Add Faq Category'  ;
            $page_description = 'Add Faq Category';
            return view('admin.content-pages.add_faq_cat', compact('page_title', 'page_description','user','id'));
    }
    public function deleteFaqCat(Request $request,$id=''){  
        $FaqCat = FaqCat::find($id);
        $FaqCat->delete();        
        return response()->json(['success' => 'Faq Category deleted successfully']);
    }
    public function retrieveFaqCat(Request $request,$id=''){  
        FaqCat::withTrashed()->find($id)->restore();      
        return response()->json(['success' => 'Faq Category Restored successfully']);
    }

    public function deleteFaq(Request $request,$id=''){  
        $faq = Faq::find($id);
        $faq->delete();
        return response()->json(['success' => 'Faq deleted successfully']);
    }

    public function restoreFaq(Request $request,$id=''){  
        Faq::withTrashed()->find($id)->restore();
        return response()->json(['success' => 'Faq restored successfully']);
    }

    public function contactUs(Request $request){
        $user = Auth::guard('admin')->user();
        $page_title = 'Contact Us'  ;
        $page_description = 'Contact Us';
        
        $start = 0;
        $query = ContactUs::get();
        
        // echo '<pre>';print($query); echo '</pre>';die;
        if($request->ajax()){
            return datatables()->of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($contact) {
                return [$contact->name, $contact->email, $contact->description];
            })
            ->make(true);
        }  

        return view('admin.content-pages.contact_list', compact('page_title', 'page_description','user'));
    }
}
