<?php
use Illuminate\Support\Facades\Route;
 
Route::get('/logout', 'LoginController@logout');
Route::get('/dashboard', 'DashboardController@index');
Route::get('/home', 'DashboardController@index')->name('home');
Route::get('/profile', 'DashboardController@profile');
Route::post('/profileupdate', 'DashboardController@profileupdate');
Route::get('/changepassword', 'DashboardController@changepassword');
Route::post('/changepassword', 'DashboardController@savepassword');

Route::get('/users', 'UsersController@index');
Route::get('/influencers-users', 'UsersController@influencers_index');
Route::post('/users', 'UsersController@index');
Route::get('/user/{id}', 'UsersController@view');
Route::get('/influencersview/{id}', 'UsersController@influencersView');
Route::get('/edituser/{id}', 'UsersController@edit');
Route::get('/influenceredit/{id}', 'UsersController@editinfluencer');
Route::post('/influenceredit', 'UsersController@influencerstore');
Route::post('/edituser', 'UsersController@store');
Route::get('/addnewuser', 'UsersController@add');
Route::get('/addinfluencer', 'UsersController@addinfluencer');
Route::get('/delete/{id}', 'UsersController@delete');
Route::get('/banuser/{id}', 'UsersController@banuser');
Route::get('/restore/{id}', 'UsersController@restore');
Route::get('/user-plans/{id}', 'UsersController@plans');

Route::get('/influencers', 'InfluencerController@index');
Route::post('/influencers', 'InfluencerController@index');
Route::get('/influencer/{id}', 'InfluencerController@view');
Route::get('/editinfluencer/{id}', 'InfluencerController@edit');
Route::post('/editinfluencer', 'InfluencerController@store');
Route::get('/addnewinfluencer', 'InfluencerController@add');
Route::get('/deleteinfluencer/{id}', 'InfluencerController@delete');
Route::get('/banfluencer/{id}', 'InfluencerController@banuser');
Route::get('/restorefluencer/{id}', 'InfluencerController@restore');
Route::get('/deactivateinfluencer/{id}', 'InfluencerController@deactivate');
Route::get('/activateinfluencer/{id}', 'InfluencerController@activate');
Route::get('/approveinfluencer/{id}', 'InfluencerController@approve');
Route::get('/rejectinfluencer/{id}', 'InfluencerController@reject');


Route::get('/home-page', 'ContentPagesController@homePage');
Route::post('/home-page', 'ContentPagesController@homePage');
Route::get('/how-it-works', 'ContentPagesController@howItWorks');
Route::post('/how-it-works', 'ContentPagesController@howItWorks');
Route::get('/home-page-top-section', 'ContentPagesController@homePageTopSection');
Route::post('/home-page-top-section', 'ContentPagesController@homePageTopSection');
Route::get('/home-page-middle-section', 'ContentPagesController@homePageMiddleSection');
Route::post('/home-page-middle-section', 'ContentPagesController@homePageMiddleSection');
Route::get('/terms-of-service', 'ContentPagesController@termsOfService');
Route::post('/terms-of-service', 'ContentPagesController@termsOfService');
Route::get('/privacy-policy', 'ContentPagesController@privacyPolicy');
Route::post('/privacy-policy', 'ContentPagesController@privacyPolicy');
Route::get('/user-agreement', 'ContentPagesController@userAgreement');
Route::post('/user-agreement', 'ContentPagesController@userAgreement');
Route::get('/faq-list/{id?}', 'ContentPagesController@faqList');
Route::get('/faq-cat-list', 'ContentPagesController@faqCatList');
Route::get('/add-edit-faq/{id?}', 'ContentPagesController@addFaq');
Route::post('/add-edit-faq/{id?}', 'ContentPagesController@addFaq');
Route::get('/add-faq-cat/{id?}', 'ContentPagesController@addFaqCat');
Route::post('/add-faq-cat/{id?}', 'ContentPagesController@addFaqCat');
Route::get('/delete-faq-cat/{id}', 'ContentPagesController@deleteFaqCat');
Route::get('/retrieve-faq-cat/{id}', 'ContentPagesController@retrieveFaqCat');
Route::get('/delete-faq/{id?}', 'ContentPagesController@deleteFaq');
Route::get('/restore-faq/{id?}', 'ContentPagesController@restoreFaq');
Route::get('/contact-list', 'ContentPagesController@contactUs');

/* Admin Bank Detail*/
Route::any('/save/bankdetails', 'DashboardController@adminbankdetail');



/* Plan Categories  */
Route::get('/categories', 'CategoryController@index');
Route::get('/addcategory', 'CategoryController@add');
Route::post('/savecategory', 'CategoryController@save');
Route::get('/plan-category/{id}', 'CategoryController@view');
Route::get('/edit-category/{id}', 'CategoryController@edit');
Route::get('/delete-category/{id}', 'CategoryController@delete');
Route::get('/restore-category/{id}', 'CategoryController@restore');
/* Manage race */
Route::get('/race', 'RaceController@index');
Route::get('/addrace', 'RaceController@add');
Route::get('/edit-manage-race/{id}', 'RaceController@edit');
Route::post('/saverace', 'RaceController@save');
Route::get('/delete-race/{id}', 'RaceController@delete');
Route::get('/restore-manage-race/{id}', 'RaceController@restore');






/* Manage Social Platforms */
Route::get('/social-platforms', 'SocialPlatformController@index');
Route::get('/social-platform/add', 'SocialPlatformController@add');
Route::post('/social-platform/save', 'SocialPlatformController@save');
Route::get('/social-platform/edit/{id}', 'SocialPlatformController@edit');
Route::get('/social-platform/delete/{id}', 'SocialPlatformController@delete');
Route::get('/social-platform/restore/{id}', 'SocialPlatformController@restore');


/* Manage campaigns */
Route::get('/campaigns', 'CampaignController@index');
Route::get('/campaign/add', 'CampaignController@add');
Route::post('/campaign/save', 'CampaignController@save');
Route::get('/campaign/edit/{id}', 'CampaignController@edit');
Route::get('/campaign/delete/{id}', 'CampaignController@delete');
Route::get('/campaign/restore/{id}', 'CampaignController@restore');

/* Manage Jobs under a campaign*/
Route::get('/campaign/{campaign_id}/jobs', 'JobController@index');
Route::get('/campaign/{campaign_id}/job/add', 'JobController@add');
Route::post('/campaign/{campaign_id}/job/save', 'JobController@save');
Route::get('/campaign/{campaign_id}/job/edit/{id}', 'JobController@edit');
Route::get('/campaign/{campaign_id}/job/delete/{id}', 'JobController@delete');
Route::get('/campaign/{campaign_id}/job/restore/{id}', 'JobController@restore');

/* Manage orphans Jobs */
Route::get('/jobs', 'JobController@index');
Route::get('/job/add', 'JobController@add');
Route::post('/job/save', 'JobController@save');
Route::get('/job/edit/{id}', 'JobController@edit');
Route::get('/job/delete/{id}', 'JobController@delete');
Route::get('/job/restore/{id}', 'JobController@restore');

/* Manage Testimonails */
Route::get('/testimonial', 'TestimonailController@index');
Route::any('/testimonial/status/update', 'TestimonailController@updatetestimonail');
Route::any('/testimonial/description', 'TestimonailController@testimonialreviews');


/* Manage Categories  */
Route::get('/manage-categories', 'CategoryController@manageCategories');
Route::get('/add-manage-category', 'CategoryController@addManageCategory');
Route::post('/save-manage-category', 'CategoryController@saveManageCategory');
Route::get('/edit-manage-category/{id}', 'CategoryController@editManageCategory');
Route::get('/delete-manage-category/{id}', 'CategoryController@deleteManageCategory');
Route::get('/restore-manage-category/{id}', 'CategoryController@restoreManageCategory');



/* Plans  */
Route::get('/plan-category/{cat_id}/add-plan', 'CategoryController@addPlan');
Route::post('/plan-category/{cat_id}/save', 'CategoryController@savePlan');
Route::get('/plan-category/{cat_id}/edit-plan/{id}', 'CategoryController@editPlan');
Route::get('/delete-plan/{id}', 'CategoryController@deletePlan');
Route::get('/restore-plan/{id}', 'CategoryController@restorePlan');

/* Orders  */
Route::get('/orders', 'OrdersController@index');
Route::post('/orders', 'OrdersController@index');
Route::get('/order-items/{id}', 'OrdersController@orderItems');
Route::get('/view-order/{id}', 'OrdersController@viewOrder');

/* Transactions  */
Route::get('/transactions', 'TransactionController@index');
Route::get('/download-pdf/{id?}', 'TransactionController@downloadPdf');

/* Coupons  */
Route::get('/coupons', 'CouponsController@index');
Route::get('/add-coupon', 'CouponsController@addCoupon');
Route::any('/save-coupon/{id?}', 'CouponsController@saveCoupon');
Route::get('/edit-coupon/{id?}', 'CouponsController@addCoupon1');
Route::get('/view-coupons-details/{id}', 'CouponsController@viewcoupounsdetails');

Route::get('/web-settings', 'DashboardController@settings');
Route::post('/web-settings', 'DashboardController@saveSettings');

/* Manage Wallets  */
Route::get('/wallets', 'WalletController@index');
Route::get('/wallet/{wallet_id}/transaction', 'WalletController@transactions');
Route::get('/wallet/transactions', 'WalletController@transactions');
Route::get('/commission/wallet/transactions', 'CommissionWalletTransaction@index');
