<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PusherNotificationController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SocialMediaController ;

use App\Http\Controllers\MessageController ;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['token.auth']], function(){
Route::get('/browse', function(){
    return view('browse');
});
Route::get('/success', function(){
    return view('success');
});


// Demo routes
Route::get('/datatables', 'PagesController@datatables');
Route::get('/ktdatatables', 'PagesController@ktDatatables');
Route::get('/select2', 'PagesController@select2');
Route::get('/icons/custom-icons', 'PagesController@customIcons');
Route::get('/icons/flaticon', 'PagesController@flaticon');
Route::get('/icons/fontawesome', 'PagesController@fontawesome');
Route::get('/icons/lineawesome', 'PagesController@lineawesome');
Route::get('/icons/socicons', 'PagesController@socicons');
Route::get('/icons/svg', 'PagesController@svg');




// Quick search dummy route to display html elements in search dropdown (header search)
Route::get('/quick-search', 'PagesController@quickSearch')->name('quick-search');

Route::any('/influencer/{categoryname}', 'CampaignJobController@influencersCategoriesname');

Route::group(['namespace' => 'Auth'], function () {
	Route::post('/login', 'LoginController@login');
	Route::get('/signup', 'RegisterController@showRegiatrationForm');
	Route::get('/forgot-password', 'ForgotPasswordController@showForgotForm');
	Route::post('/forgot-password', 'ForgotPasswordController@sendResetLinkEmail');
	Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm');
	Route::post('/reset_password', 'ResetPasswordController@password_reset');
	Route::post('/register', 'RegisterController@register');
});

Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::get('/influencers', 'PagesController@influencers');
Route::get('/campaigns', 'CampaignJobController@campaigns');
Route::get('/campaign/{slug}', 'CampaignJobController@campaignJobs');
Route::post('/checkjob/applications', 'CampaignJobController@checkJobApplications');

Route::get('/influencers/{category}', 'CampaignJobController@influencersCategories');
Route::get('/influencers/{category}/{platform_cat}/{id}', 'CampaignJobController@influencersPlatformCategories');
Route::any('/pagination/influencersCat', 'CampaignJobController@influencersCat');
Route::post('/influencers/search', 'CampaignJobController@search');
Route::get('/jobs', 'CampaignJobController@jobs');
Route::post('/influencers', 'PagesController@influencers');
Route::get('/be-influencer', 'PagesController@beInfluencer');
Route::post('/platform/categories', 'PagesController@platformCat');



//for app created by vijay 
Route::get('/become-influencer/{id}', 'PagesController@becomeInfluencer');
Route::post('/verify-details', 'PagesController@verifyDetails');


Route::get('/how-it-works', 'PagesController@howItWorks');
Route::get('/terms-of-service', 'PagesController@termsOfService');
Route::get('/privacy-policy', 'PagesController@privacyPolicy');
Route::get('/faq', 'PagesController@faq');
Route::get('/user-agreement', 'PagesController@userAgreement');
Route::get('/contact-us', 'PagesController@contactUs');
Route::post('/contact-us', 'PagesController@contactUs');
Route::get('/influencer/{username}/profile', 'PagesController@influencerProfile');

Auth::routes();

Route::post('/transfer_complete', 'WebhookController@transferComplete');


/*User/Influencer Login*/
Route::group(['middleware' => array('auth', 'web')], function () {
	Route::get('/profile-settings', 'PagesController@profileSettings');
	Route::post('/profile-settings', 'PagesController@profileStore');
	Route::post('/be-influencer', 'PagesController@storeInfluencer');
	Route::post('/update_profile_details', 'UserController@storeInfluencer');
	Route::get('/my-profile', 'UserController@myProfile');
	Route::get('/change-password', 'UserController@changePassword');
	Route::post('/change-password', 'UserController@saveChangePassword');
	Route::get('/logout', 'HomeController@logout');
	Route::get('/plan-setting', 'UserController@planSetting');
	Route::post('/plan-setting', 'UserController@storePlanSetting');
	Route::post('/custom-plan-setting/{id}', 'UserController@storeCustomPlanSetting');
	Route::post('/add-ratings', 'UserController@addRatings');
	Route::post('/mark-done', 'UserController@markDone');
	Route::get('/reviews/{id}', 'UserController@reviews');
	Route::post('/requestOTP', 'UserController@requestOTP');
	Route::post('/confirmOTP', 'UserController@confirmOTP');
	Route::get('/disable2fa', 'UserController@disable2fa');
	Route::get('/bank-settings', 'UserController@bankSettings');
	Route::post('/savebankdetails', 'UserController@savebankdetails');
	Route::post('/deletebankaccount', 'UserController@deletebankaccount');
	
	Route::get('/wallet', 'UserController@wallet');
	Route::get('/invoicepdf/{id}', 'UserController@invoicepdf');

	Route::any('/fund-wallet', 'UserController@fundWallet');
	Route::any('/withrawal-wallet-amount', 'UserController@withrawalWalletAmount');
	Route::any('/withrawal-amount-request', 'UserController@withrawalAmountRequest');
	Route::post('/load-wallet', 'UserController@loadWallet');
	

	Route::get('/addtocart/{id}', 'CartController@add');
	Route::get('/cart', 'CartController@cart');
	Route::get('/removefromcart/{id}', 'CartController@remove');
	// Route::post('/placeorder', 'CartController@placeorder');
	Route::get('/orders', 'OrderController@orders');
	Route::post('/acceptorder', 'OrderController@accept');

	Route::post('/applycoupon', 'CartController@applycoupon');
	Route::post('/removecoupon', 'CartController@removecoupon');

	Route::post('/removecard', 'CartController@removecard');
	Route::get('/send-pdf/{order_id?}', 'CartController@sendPdf');

	Route::get('/transactions', 'OrderController@transactions');
	Route::post('/transactions', 'OrderController@transactions');

	Route::get('/download_pdf/{id}', 'OrderController@download');

	/* Manage Campaign Job management */
	Route::get('/create/campaign', 'CampaignJobController@createCampaign');	
	Route::post('/create/campaign', 'CampaignJobController@saveCampaign');	
	
	Route::get('edit/campaign/{slug}', 'CampaignJobController@createCampaign');	

	Route::get('campaign/{slug}/create/job', 'CampaignJobController@createJob');
	Route::post('campaign/{slug}/create/job', 'CampaignJobController@saveJob');	
	Route::get('campaign/{slug}/edit/job/{jobSlug}', 'CampaignJobController@createJob');

	Route::get('job/{slug}', 'CampaignJobController@jobDetails');
	Route::get('job/{slug}/status', 'CampaignJobController@status');
	Route::get('job/{slug}/delete', 'CampaignJobController@delete');
	Route::get('job/{slug}/edit', 'CampaignJobController@createJob');

	Route::post('upload/job/attachments', 'CampaignJobController@saveAttachments');	
	Route::post('remove/job/attachments', 'CampaignJobController@removeAttachments');	

	Route::get('/my-jobs', 'CampaignJobController@myJobs');
	Route::get('/my-campaigns', 'CampaignJobController@myCampaigns');
	

	Route::get('job/{slug}/proposal/submit', 'CampaignJobController@applyJob');
	Route::post('job/{slug}/submit/proposal', 'CampaignJobController@submitProposal');
	Route::post('job/{slug}/submit/post-url', 'CampaignJobController@submitPostUrl');
	Route::get('job/{slug}/proposals', 'CampaignJobController@proposals');
	Route::get('job/{slug}/proposal/{id}', 'CampaignJobController@proposal');
	
	Route::get('order-download-zip/{item}', 'OrderController@downloadZip');
	

	Route::get('hire-influencer/{username?}', 'CampaignJobController@hireInfluencer');
	Route::get('create-job', 'CampaignJobController@hireInfluencer');
	Route::post('hire-influencer', 'CampaignJobController@saveJob')->name('hire-influencer');


	// Route for hire jobs
	Route::any('/my-active-jobs', 'CampaignJobController@myActiveJobs');
	Route::post('hire-jobs', 'CampaignJobController@hirejobs');
	Route::post('approve-job-post', 'CampaignJobController@apporvedjobpost');
	Route::any('job/reviews/{id}', 'CampaignJobController@jobsreviews');
	Route::any('job/reviews1/{id}', 'CampaignJobController@jobsreviews1')->name('job.reviews1');
	Route::any('job/submitreviews', 'CampaignJobController@submitreviews');
	Route::any('job/jobcompletes/{id}', 'CampaignJobController@jobcompletes');
	Route::any('add/testimonial', 'CampaignJobController@addtestimonial');
	Route::any('submit/testimonial', 'CampaignJobController@submittestimonial');
	Route::any('job/jobdonebyuser/{id?}', 'CampaignJobController@jobdonebyuser');
	Route::post('ckeditor/upload', 'CkeditorController@upload')->name('ckeditor.upload');
	


	// Route for notifications
	Route::any('notification', 'NotificationController@index')->name('notification.list');
	Route::get('notification/{id}/delete', 'NotificationController@delete');
	Route::get('notification/clear', 'NotificationController@clear');

	


	Route::get('message/{jobId?}/{user_id?}', 'MessageController@index');
	Route::post('api/getUserDetails', [MessageController::class, 'getUserDetails']);
	Route::post('api/useractive', [MessageController::class, 'useractive']);
});

Route::group(['middleware' => array('auth', 'web','afterresponse')], function () {
	Route::post('/placeorder', 'CartController@placeorder');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
	Route::get('/', 'LoginController@showLoginForm')->name('home');
	Route::get('/login', 'LoginController@showLoginForm');
	Route::post('/login', 'LoginController@login');
	Route::post('/forgotpassword', 'ForgotPasswordController@sendResetLinkEmail');
	Route::get('/view-email', 'ForgotPasswordController@viewEmail');
	Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm');
	Route::post('/reset_password', 'ResetPasswordController@password_reset');
});
Route::group(['prefix' => 'admin',  'namespace' => 'Admin', 'middleware' => array('is_admin', 'web')], function () {
	require(__DIR__ . '/Backend/admin.php');
});



Route::get('/instacallback', 'UserController@instacallback');

Route::get('auth/facebook', [SocialMediaController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [SocialMediaController::class, 'facebookSignin']);

Route::get('verify/facebook', [SocialMediaController::class, 'redirectToFacebook']);
Route::get('verify/facebook/callback', [SocialMediaController::class, 'facebookSignin']);

Route::get('auth/instagram', [SocialMediaController::class, 'redirectToInstagram']);
Route::get('auth/instagram/callback', [SocialMediaController::class, 'instagramSignin']);

Route::get('verify/instagram', [SocialMediaController::class, 'redirectToInstagram']);
Route::get('verify/instagram/callback', [SocialMediaController::class, 'instagramSignin']);

Route::get('auth/youtube', [SocialMediaController::class, 'redirectToYoutube']);
Route::get('auth/youtube/callback', [SocialMediaController::class, 'youtubeSignin']);

Route::get('verify/youtube', [SocialMediaController::class, 'redirectToYoutube']);
Route::get('verify/youtube/callback', [SocialMediaController::class, 'youtubeSignin']);

Route::get('auth/twitter', [SocialMediaController::class, 'redirectToTwitter']);
Route::get('auth/twitter/callback', [SocialMediaController::class, 'twitterSignin']);

Route::get('verify/twitter', [SocialMediaController::class, 'redirectToTwitter']);
Route::get('verify/twitter/callback', [SocialMediaController::class, 'twitterSignin']);

Route::get('auth/tiktok', [SocialMediaController::class, 'redirectToTiktok']);
Route::get('auth/tiktok/callback', [SocialMediaController::class, 'tiktokSignin']);

Route::get('verify/tiktok', [SocialMediaController::class, 'redirectToTiktok']);
Route::get('verify/tiktok/callback', [SocialMediaController::class, 'tiktokSignin']);


});

Route::get('download-zip/{slug}/{iswebview?}', 'CampaignJobController@downloadZip');