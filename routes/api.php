<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController ;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('savemessage', [MessageController::class, 'savemessage']);
Route::any('markseenmsgs', [MessageController::class, 'markseenmsgs']);
Route::any('markreadmsgs', [MessageController::class, 'markreadmsgs']);
Route::any('chatonstatus', [MessageController::class, 'chatonstatus']);
Route::any('useractives', [MessageController::class, 'useractives']);

Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::post('/loginOTP', 'Api\AuthController@loginOTP');
Route::post('/verifyOTP', 'Api\AuthController@verifyOTP');
Route::post('/resendOTP', 'Api\AuthController@resendOTP');
Route::post('/forget-password', 'Api\AuthController@forgetPassword');
Route::post('/create-new-password', 'Api\AuthController@createNewPassword');
Route::get('/influencers', 'Api\InfluencerController@list');
Route::get('/influencer/{username}/profile', 'Api\PagesController@influencerProfile');
Route::get('/logout', 'Api\AuthController@logOut');
Route::get('/countries', 'Api\PagesController@countries');
Route::get('/races', 'Api\PagesController@races');
Route::get('/browse-jobs', 'Api\CampaignJobController@browseJobs');
Route::get('/campaigns', 'Api\CampaignJobController@campaigns');
Route::get('/social-platform', 'Api\PagesController@socialPlatform');

Route::get('/categories', 'Api\PagesController@categories');

Route::group(array('middleware' => array('token_auth')), function () {
	Route::get('/get-account-info', 'Api\PagesController@getAccountInfo');
	Route::post('/post-account-info', 'Api\PagesController@postAccountInfo');
	Route::post('/updateProfileImage', 'Api\PagesController@updateProfileImage');
	Route::post('/fa-send-otp', 'Api\PagesController@sendOTP');
	Route::post('/enable-fa', 'Api\PagesController@confirmOTP');
	Route::post('/disable-fa', 'Api\PagesController@disable2fa');
	
	Route::post('/add-plan', 'Api\PagesController@addPlan');
	Route::get('/myplans', 'Api\PagesController@myplans');
	Route::post('/update-plan-setting', 'Api\PagesController@updatePlanSetting');

	Route::post('/become-influencer', 'Api\PagesController@becomeInfluencer');
	Route::post('/savebankdetails', 'Api\InfluencerController@savebankdetails');
	Route::get('/getbankdetails', 'Api\InfluencerController@getbankdetails');
	Route::get('/deletebankdetails', 'Api\InfluencerController@deletebankaccount');
	Route::post('/change-password', 'Api\InfluencerController@saveChangePassword');
	Route::get('/my-reviews', 'Api\InfluencerController@myReviews');	
	Route::get('/addtocart/{id}', 'Api\CartController@add');
	Route::post('/deleteItem', 'Api\CartController@deleteItem');
	Route::get('/mycart', 'Api\CartController@myCart');
	Route::get('/getCards', 'Api\CartController@getCards');
	Route::post('/addRatings', 'Api\CartController@addRatings');


	Route::get('/orders', 'Api\PagesController@getOrders');
	Route::post('/accept-order', 'Api\PagesController@acceptOrder');
	Route::get('/view-order', 'Api\PagesController@viewOrder');
	
	Route::get('/transactions', 'Api\PagesController@transactions');
	
	Route::post('/apply-coupon', 'Api\CartController@applyCoupon');
	Route::post('/remove-coupon', 'Api\CartController@removeCoupon');

	Route::post('add-job', 'Api\CampaignJobController@saveJob')->name('add-job');
	Route::get('/jobs', 'Api\CampaignJobController@jobs');
	Route::post('/submit-post-url', 'Api\CampaignJobController@submit_post_url');
	Route::get('/get-wallet', 'Api\WalletController@get_wallet');
	Route::post('/create-campaign', 'Api\CampaignJobController@create_campaign');
	Route::get('/campaigns-list', 'Api\CampaignJobController@campaigns_list');
	Route::get('/my-campaigns-list', 'Api\CampaignJobController@my_campaigns_list');
	Route::get('/my-jobs', 'Api\CampaignJobController@myJobs');
	
	Route::get('/notifications', 'Api\PagesController@notifications');
	Route::post('notification/delete', 'Api\PagesController@notificationDelete');
	Route::get('notification/clear', 'Api\PagesController@notificationClear');
});
Route::group(array('middleware' => array('token_auth', 'afterresponse')), function () {
	Route::post('/placeorder', 'Api\CartController@placeOrder');
});

