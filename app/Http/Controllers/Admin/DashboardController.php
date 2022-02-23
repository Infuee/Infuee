<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use \App\http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Admin;
use App\Models\Setting;
use App\Models\InfluencerRequests;
use App\User;
use App\Models\Orders;
use App\Models\Transactions;
use carbon\Carbon;
use App\Models\UserWallet;
use Stripe;
use App\Models\BankSetting;
use App\Models\Countries;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();
        $page_title = 'Dashboard';
        $page_description = 'Some description for the page';
       
        $users = User::where('type', User::TYPE_USER)->count();
        $influencers = User::where('type', User::TYPE_INFLUENCER)->count();
        $influencerRequests = InfluencerRequests::whereIn('status', [0,2])->count();
        $orders = Orders::count();

        $walletAmount = UserWallet::sum('amount');

        $orders_['data'] = [];
        $orders_['category'] =[]; 
        $commision['data'] = [];
        $commision['category'] =[];

        $type = $request->get('type')?:1;

        if($type == 1){
            $format = "l, jS M";
            $compareFo = "Y-m-d";
            $begin = new \DateTime(date('Y-m-1'));
            $end = new \DateTime(date('Y-m-d', strtotime('+1 days')));
            $interval = \DateInterval::createFromDateString('1 day');
        }else if($type == 2){
            $format = "l, jS M";
            $compareFo = "Y-m-d";
            $date = strtotime('-7 days');
            $begin = new \DateTime(date('Y-m-d', $date ));
            $end = new \DateTime(date('Y-m-d', strtotime('+1 days')));
            $interval = \DateInterval::createFromDateString('1 day');
        }else if($type == 3){
            $format = "l, jS M";
            $compareFo = "Y-m-d";
            $begin = new \DateTime(date('Y-'.(date('m')-1).'-1'));
            $end = new \DateTime(date('Y-m-d', strtotime('+1 days')));
            $interval = \DateInterval::createFromDateString('1 day');
        }else if($type == 4){
            $format = "M Y";
            $compareFo = "Y-m-";
            $begin = new \DateTime(date('Y-'.abs(date('m')-6).'-1'));
            $end = new \DateTime(date('Y-m-31'));
            $interval = \DateInterval::createFromDateString('1 month');
        }else if($type == 5){
            $format = "M Y";
            $compareFo = "Y-m-";
            $begin = new \DateTime(date((date('Y')-1).'-m-1'));
            $end = new \DateTime(date('Y-m-31'));
            $interval = \DateInterval::createFromDateString('1 month');
        }


        $period = new \DatePeriod($begin, $interval, $end);
        foreach ($period as $key => $dt) {
            $orders_['category'][] = $dt->format($format);
            $commision['category'][] = $dt->format($format);
            $orders_['data'][] = Orders::where('created_at', 'LIKE', "%{$dt->format($compareFo)}%")->count();
            $commision['data'][] = number_format(\App\Models\UserWalletTransaction::where('created_at', 'LIKE', "%{$dt->format($compareFo)}%")->sum('commission') , 2);
        }
        
        $adminCommition = '$ ' . number_format(\App\Models\UserWalletTransaction::sum('commission'), 2) ;

        return view('admin.dashboard', compact('page_title', 'page_description', 'user', 'users', 'influencers', 'influencerRequests', 'orders', 'orders_', 'commision', 'type', 'walletAmount', 'adminCommition'));
    }

    public function profile(){
        $user = Auth::guard('admin')->user();
        $page_title = $user['name']. ' Profile Page';
        $page_description = $user['name']. ' Profile Page';

        return view('admin.profile', compact('page_title', 'page_description', 'user'));
    }

    public function profileupdate(Request $request){
        $user = Auth::guard('admin')->user();
        $user->first_name = $request->get('first_name')?:$user->first_name;
        $user->last_name = $request->get('last_name')?:$user->last_name;
        $user->phone = $request->get('phone')?:$user->phone;
        $user->email = $request->get('email')?:$user->email;
        $user->address = $request->get('address')?:$user->address;
        
        if ($request->profile_avatar) {
            $image = $request->file('profile_avatar');
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path("/media/users");
            $image->move($destinationPath , $imagename);         
            $user->image = $imagename;
        }
        $user->save();
        $request->session()->flash('success', 'Profile updated successfully.');
        return redirect()->intended('admin/profile');
    }

    public function changepassword(){
        $user = Auth::guard('admin')->user();
        $page_title = $user['name']. ' Change Password';
        $page_description = $user['name']. ' Change Password';

        return view('admin.changepassword', compact('page_title', 'page_description', 'user'));
    }

        // Validate Profile form data
    public function PasswordVal(array $data)
    {
        return Validator::make($data, [
            'password' => 'required|string|min:8',
        ]);
    }

    public function savepassword(Request $request){
        $profileData =  $request->all();
        $validator = $this->PasswordVal($profileData);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $user = Auth::guard('admin')->user();
        if(Hash::check($request->cpassword, $user->password)){
            $chngePassword =  Admin::where('email', Auth::guard('admin')->user()->email)->first();
            $chngePassword->password = bcrypt($request->password);
            $chngePassword->save();
            $message = 'Password updated successfully.';
            return redirect('/admin/changepassword')->with(['success' => $message]);
        }
        $message = 'Old password is wrong';
        return redirect('/admin/changepassword')->with(['error' => $message]);
    }

    public function settings(){
        $user = Auth::guard('admin')->user();
        $setting = Setting::first();
        $page_title = 'Setting';
        $page_description = 'Website Settings';
        return view('admin.setting', compact('page_title', 'page_description', 'user', 'setting'));

    }

    public function saveSettings(Request $request){
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'twilio_accont_sid' => 'required',
            'twilio_auth_token' => 'required',
            'twilio_from' => 'required',
            'google_api_key' => 'required',
            'stripe_pk' => 'required',
            'stripe_sk' => 'required',
            'stripe_currency' => 'required',
            'smtp_username' => 'required',
            'smtp_password' => 'required',
            'commission' => 'required',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $setting = Setting::first();

        $setting->twilio_accont_sid = $inputs['twilio_accont_sid'];
        $setting->twilio_auth_token = $inputs['twilio_auth_token'];
        $setting->twilio_from = $inputs['twilio_from'];
        $setting->google_api_key = $inputs['google_api_key'];
        $setting->stripe_pk = $inputs['stripe_pk'];
        $setting->stripe_sk = $inputs['stripe_sk'];
        $setting->stripe_currency = $inputs['stripe_currency'];
        $setting->smtp_username = $inputs['smtp_username'];
        $setting->smtp_password = $inputs['smtp_password'];
        $setting->commission = $inputs['commission'];
        $setting->save();

        return redirect('/admin/web-settings')->with(['alert' => 'Settings saved successfully']);

    }

    protected function BankValidate(array $inputs)
    {
        return  $validator = Validator::make(
            $inputs,
            [
                'account_holder' => 'required',
                'account_number' => 'required',
                'sortCode' => 'required',
                'personal_id_number' => 'required',
                'documentFront' => 'required|mimes:jpeg,png,jpg|max:5000',
                'documentBack' => 'required|mimes:jpeg,png,jpg|max:5000',
                'additionalDocumentFront' => 'nullable|required_with:additionalDocumentBack|mimes:jpeg,png,jpg|max:5000',
                'additionalDocumentBack' => 'nullable|required_with:additionalDocumentFront|mimes:jpeg,png,jpg|max:5000',
            ],
            [
                'documentFront.max' => ' The PHOTO ID front may not be greater than 5 mb',
                'documentBack.max' => ' The PHOTO ID back may not be greater than 5 mb',
                'additionalDocumentFront.max' => ' The additional document front may not be greater than 5 mb',
                'additionalDocumentBack.max' => ' The additional document back may not be greater than 5 mb',
            ]
        );
    }

    public function deletebankaccount(Request $request){
        $user = auth()->user();
        $bankSetting = BankSetting::where('user_id', $user['id'])->first();
        $settings = Setting::first();
        $stripe = new Stripe();
        $stripe = Stripe::make($settings['stripe_sk']);
        $bank = $stripe->account()->delete($bankSetting['account_id']);
        $bankSetting->account_id = null;
        $bankSetting->save();
        return response()->json(['success'=>true, 'message'=> "Account deleted successfully, Please add another bank account to accepts orders."]);
    }

    public function adminbankdetail( Request $request ){
        $name1 = '';
        $name2 = '';
        $inputs = $request->all();
        //dd($inputs);
        $validator = $this->BankValidate($inputs);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        } 
        $user =     Admin::first();
        
        $dob_check = $user['date_of_birth'];
            
        $request->documentFront;

        if ( $dob_check) {

            $name =   preg_match('/\s/', $request->account_holder);
            #detect white-space
            if ($name == 1) {
                $name_ex = explode(' ', $request->account_holder);
                $name1 = $name_ex[0];
                $name2 = $name_ex[1];
            } else {
                $name1 = $user['first_name'];
                $name2 = $user['last_name'];
            }
            try {
                $dob = explode('-', $user['date_of_birth']);
                $settings = Setting::first();
                $country = Countries::where('id', $user['country_code'])->first();

                $stripe = new Stripe();
                $stripe = Stripe::make($settings['stripe_sk']);
                // if (!$user['stripe_connect_account']) {

                $token = $stripe->tokens()->create([
                    'bank_account' => [
                        'country'  => "IN",
                        'currency' => "INR",
                        'account_holder_name' => $request->account_holder,
                        'routing_number' => $request->sortCode,
                        'account_number' => $request->account_number,
                    ],
                ]);
                $purpose = 'identity_document';
                $documentFront = $stripe->files()->create($request->documentFront, $purpose);
                $documentBack = $stripe->files()->create($request->documentBack, $purpose);
                if ($request->documentBack) {
                    $additionalDocumentFront = $stripe->files()->create($request->documentBack, $purpose);
                }
                if ($request->documentBack) {
                    $additionalDocumentBack = $stripe->files()->create($request->documentBack, $purpose);
                }

                $account = $stripe->account()->create([
                    'country' => "IN",
                    'type' => 'custom',
                    'email' => $user['email'],
                    'legal_entity' => [
                        'first_name' => @$name1 ?: $user['first_name'],
                        'last_name' => @$name2 ?: $user['last_name'],
                        'personal_id_number' => $inputs['personal_id_number'],
                        'type' => 'individual',
                        'dob' => [
                            'day' => @$dob[2],
                            'month' => @$dob[1],
                            'year' => @$dob[0],
                        ],
                        "address" => [
                            "line1" => $user['address'],
                            "line2" => '',
                            "city" => $user['city'],
                            'country' => @$country['ISO_code'],
                            "postal_code" => @$country['code']?'+'.$country['code']:'+91',
                            // "state" => $user['state'],
                        ],
                        "verification" => [
                            "additional_document" => !empty(@$additionalDocumentFront) ? $additionalDocumentFront['id'] : null,
                            "additional_document_back" => !empty(@$additionalDocumentBack) ? $additionalDocumentBack['id'] : null,
                            "document" => $documentFront['id'],
                            "document_back" =>  $documentBack['id'],
                        ]
                    ],
                    'capabilities' => [
                        'card_payments' => [
                            'requested' => 'true'
                        ],
                        'transfers' => [
                            'requested' => 'true'
                        ],
                    ],
                    'tos_acceptance' => ['date' => strtotime(date('Y-m-d h:i:s a')), 'ip' => $_SERVER['REMOTE_ADDR']],
                    'external_account' => $token['id'],
                ]);
                
                
             
                $userBankSetting = Admin::where('user_id', $user['id'])->first();
                if(empty($userBankSetting)){
                    $userBankSetting = new Admin();
                    $userBankSetting->user_id = $user['id'];
                }
                $userBankSetting->bank_id = $token['id'];
                $userBankSetting->account_id = $account['id'];
                $userBankSetting->save();

                return redirect()->back()->with(['success' => "Bank Account details saved successfully."]);

            }

             catch (\Cartalyst\Stripe\Exception\BadRequestException $e) {
                return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
            } catch (\Cartalyst\Stripe\Exception\UnauthorizedException $e) {
                return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
            } catch (\Cartalyst\Stripe\Exception\InvalidRequestException $e) {
                return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
            } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
                return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
            } catch (\Cartalyst\Stripe\Exception\ServerErrorException $e) {
                return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
            } catch (\Cartalyst\Stripe\Exception\NotFoundException $e) {
                return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
            } catch (\Cartalyst\Stripe\Exception\ServerErrorException $e) {
                return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
            } catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
                return redirect()->back()->withInput()->with(['error' => $e->getMessage()], $e->getCode(), ['code' => $e->getCode(), 'status_' => 'error']);
            }
            SendNotification::where(['from' => Auth::guard('user')->user()->_id, 'to' => Auth::guard('user')->user()->_id, 'type' => 'account'])->update([
                'seen' => 1,
            ]);
            return redirect()->back()->with(['success' => 'Bank account added successfully', 'tabOpen' => 'test3']);
        } 
        return redirect()->back()->with(['error' => 'Please Provide required information like Date of birth , address', 'tabOpen' => 'test1']);

    }

}
