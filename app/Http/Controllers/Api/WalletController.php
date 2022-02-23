<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\UserPlans;
use App\Models\Categories;
use App\Models\Setting;
use App\Models\Countries;
use App\Models\BankSetting;
use App\Models\CartItems;
use App\Models\UserWallet;
use App\Models\UserWalletTransaction;
use App\Models\Cart;
use App\Models\OrderItems;
use App\Models\Rating;
use Illuminate\Http\Request;
use Mail;
Use URL;
use JWTAuth;
use DB;
use Stripe;
use Helpers;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\Orders;
use App\Models\Transactions;
use App\Models\Coupons;
use App\Models\CouponAppliences;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="api.host.com",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="MNG Apis",
 *         description="Api description...",
 *         termsOfService="",
 *         @SWG\Contact(
 *             email="@.com"
 *         ),
 *         @SWG\License(
 *             name="Private License",
 *             url="URL to the license"
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="Find out more about my website",
 *         url="http..."
 *     )
 * )
 */


class WalletController extends Controller
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
        // $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
     /**
     * @OA\Get(
     ** path="/api/get-wallet",
     *   tags={"User"},
     *   summary="Get-Wallett",
     *   operationId="get-wallet",
     *   security={{ "apiAuth": {} }},
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *   @OA\Response(
     *      response=403,
     *      description="Forbidden"
     *   )
     *)
     **/


    public function get_wallet(Request $request){
        $user = JWTAuth::parseToken()->authenticate();
        $wallet = \App\Models\UserWallet::where('user_id', $user['id'])->first();
        $transactions = \App\Models\UserWalletTransaction::where('wallet_id', @$wallet['id'] )->with(['user'])->latest()->paginate(20);
             $message="Wallet Details";
            foreach($transactions as $key => $transaction){
            $transactions[$key]['invoice_pdf'] =  URL::to('invoicepdf',$transaction['transaction_id']);
            }
        return $this->respondData(200,$message,['transactions' => $transactions]);     

    }




}
