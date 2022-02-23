<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Countries;
use App\Models\Race;
use Illuminate\Http\Request;
use Mail;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function register(Request $request)
    {

        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'first_name' => 'required|string',
            // 'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            // 'city' =>'required',
            // 'state' =>'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $raw = [
            'first_name' => $inputs['first_name'],
            'last_name' => @$inputs['last_name'],
            'email' => $inputs['email'], 
            'password' => Hash::make($request->get('password')), 
            'phone' => $inputs['phone'], 
            'followers' => 0, 
            'status' => User::STATUS_ACTIVE, 
            'type' => User::TYPE_USER, 
            'image' => '', 
            'country' => $inputs['country'], 
            'country_code' => @$inputs['country_code'], 
            'race_id' => @$inputs['race_id'], 
            'city' => @$inputs['city'], 
            'state' => @$inputs['state'], 
            'school' => $inputs['school'],
            'date_of_bith' => date('Y-m-d', strtotime(@$inputs['dob']) ) ,
            'address' => @$inputs['address'],
            'lat' => @$inputs['lat'],
            'lng' => @$inputs['lng'],
        ];
        if($user = User::create($raw)){
            $createWalletAcc = array(
                'user_id' => $user['id'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            );
            \App\Models\UserWallet::insert($createWalletAcc);
            $email = $user['email'];
            try{
                Mail::send('email.signup', $confirmed = array('user_info'=>$user), function($message ) use ($email){
                    $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Infuee ! Welcome')
                    ->to($email);
                });
            }
            catch(\Exception $e){}

            return redirect('login')->with(['success' => "Your account is registered successfully."]);
        }
        return redirect()->back()->with(['error' => 'Something went wrong. Please try again']);
    }

    public function showRegiatrationForm(){
        $countries = Countries::all();
        $race = Race::where('status','1')->get();
        return view('auth.register', compact('countries','race'));
    }
}
