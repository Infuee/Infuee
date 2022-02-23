<?php
// Dashboard
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Dashboard', url('/admin/dashboard'));
});

// home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Dashboard', url('/admin/dashboard'));
});


// Users
Breadcrumbs::for('users', function ($trail) {
	$trail->parent('dashboard');
    $trail->push('Users', url('/admin/users'));
});
// Influencers
Breadcrumbs::for('influencers-users', function ($trail) {
    $trail->push('influencers', url('admin/influencers-users'));
});

Breadcrumbs::for('influencersview', function ($trail) {
    $trail->push('influencers', url('admin/influencersview'));
});

Breadcrumbs::for('addinfluencer', function ($trail) {
    $trail->push('Add Influencers', url('admin/addinfluencer'));
});
Breadcrumbs::for('influenceredit', function ($trail) {
    $trail->push('Edit Influencers', url('admin/influenceredit'));
});

 
Breadcrumbs::for('addnewuser', function ($trail) {
	$trail->parent('users');
    $trail->push('Add User');
});

Breadcrumbs::for('edituser', function ($trail) {
	$trail->parent('users');
    $trail->push('Edit User');
});

Breadcrumbs::for('user', function ($trail) {
	$trail->parent('users');
    $trail->push('User');
});

// Influencer Requests
Breadcrumbs::for('influencers', function ($trail) {
	$trail->parent('dashboard');
    $trail->push('Influencers Requests', url('/admin/influencers'));
});

Breadcrumbs::for('influencer', function ($trail) {
	$trail->parent('influencers');
    $trail->push('Influencer');
});


// Influencer Requests
Breadcrumbs::for('home-page', function ($trail) {
	$trail->parent('dashboard');
    $trail->push('Home Page', url('/admin/home-page'));
});
Breadcrumbs::for('how-it-works', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('How it works', url('/admin/how-it-works'));
});
Breadcrumbs::for('home-page-top-section', function ($trail) {
    $trail->push('Home Page Top Section', url('admin/home-page-top-section'));
});
Breadcrumbs::for('home-page-middle-section', function ($trail) {
    $trail->push('Home Page Middle Section', url('admin/home-page-middle-section'));
});

Breadcrumbs::for('terms-of-service', function ($trail) {
	$trail->parent('dashboard');
    $trail->push('Terms of Service', url('/admin/terms-of-service'));
});

Breadcrumbs::for('privacy-policy', function ($trail) {
	$trail->parent('dashboard');
    $trail->push('Privacy Policy', url('/admin/privacy-policy'));
});

Breadcrumbs::for('faq-list', function ($trail) {
	$trail->parent('dashboard');
    $trail->push('FAQ List', url('/admin/faq-list'));
});

Breadcrumbs::for('faq-cat-list', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('FAQ List', url('/admin/faq-cat-list'));
});

Breadcrumbs::for('add-edit-faq', function ($trail) {
    $trail->parent('faq-list');
    $trail->push('Add FAQ', url('/admin/add-edit-faq'));
});
Breadcrumbs::for('add-faq-cat', function ($trail) {
    $trail->parent('faq-list');
    $trail->push('Add FAQ Category', url('/admin/add-faq-cat'));
});

Breadcrumbs::for('user-agreement', function ($trail) {
	$trail->parent('dashboard');
    $trail->push('User Agreement', url('/admin/user-agreement'));
});

Breadcrumbs::for('contact-list', function ($trail) {
	$trail->parent('dashboard');
    $trail->push('Contact List', url('/admin/contact-listt'));
});

Breadcrumbs::for('profile', function ($trail) {
	$trail->parent('dashboard');
    $trail->push('Profile', url('/admin/profile'));
});

Breadcrumbs::for('changepassword', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Change Password', url('/admin/changepassword'));
});

Breadcrumbs::for('categories', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Plan Categories', url('/admin/categories'));
});
Breadcrumbs::for('race', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Race', url('/admin/race'));
});
Breadcrumbs::for('addrace', function ($trail) {
    $trail->push('Add Race', url('admin/addrace'));
});
Breadcrumbs::for('edit-manage-race', function ($trail) {
    $trail->push('Edit Race', url('admin/edit-manage-race/'));
});

Breadcrumbs::for('manage-categories', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Categories', url('/admin/manage-categories'));
});

Breadcrumbs::for('add-manage-category', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Add Categories', url('/admin/add-manage-category'));
});

Breadcrumbs::for('view-manage-category', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Category', url('/admin/view-manage-category'));
});

Breadcrumbs::for('edit-manage-category', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Edit Category', url('/admin/edit-manage-category'));
});

Breadcrumbs::for('addcategory', function ($trail) {
    $trail->parent('categories');
    $trail->push('Add Plan Categories', url('/admin/addcategory'));
});

Breadcrumbs::for('plan-category', function ($trail) {
    $trail->parent('categories');
    $trail->push('Category', url('/admin/plan-category'));
});
Breadcrumbs::for('edit-category', function ($trail) {
    $trail->parent('categories');
    $trail->push('Update Category', url('/admin/edit-category'));
});
// Users
Breadcrumbs::for('user-plans', function ($trail) {
    $trail->parent('users');
    $trail->push('User Plans', url('/admin/users'));
});

Breadcrumbs::for('web-settings', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Website Settings', url('/admin/web-settings'));
});
//Orders
Breadcrumbs::for('orders', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Orders', url('/admin/orders'));
});

Breadcrumbs::for('order-items', function ($trail) {
    $trail->parent('orders');
    $trail->push('Orders Items', url('/admin/order-items'));
});

Breadcrumbs::for('view-order', function ($trail) {
    $trail->parent('orders');
    $trail->push('Orders', url('/admin/view-order'));
});

//Transaction
Breadcrumbs::for('transactions', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Transactions', url('/admin/transactions'));
});

//Coupons
Breadcrumbs::for('coupons', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Coupons', url('/admin/coupons'));
});

Breadcrumbs::for('add-coupon', function ($trail) {
    $trail->parent('coupons');
    $trail->push('Add Coupon', url('/admin/add-coupon'));
});

Breadcrumbs::for('edit-coupon', function ($trail) {
    $trail->parent('coupons');
    $trail->push('Edit Coupon', url('/admin/edit-coupon'));
});

Breadcrumbs::for('download-pdf', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Download Pdf', url('/admin/download-pdf'));
});

Breadcrumbs::for('social-platforms', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Social Platforms', url('/admin/social-platforms'));
});
Breadcrumbs::for('social-platform-add', function ($trail) {
    $trail->parent('social-platforms');
    $trail->push('Add Social Platform', url('/admin/social-platform/add'));
});

Breadcrumbs::for('social-platform-edit', function ($trail) {
    $trail->parent('social-platforms');
    $trail->push('Edit Social Platform', url('/admin/social-platform/edit'));
});

Breadcrumbs::for('campaigns', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Campaigns', url('/admin/campaigns'));
});

Breadcrumbs::for('jobs', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Jobs', url('/admin/jobs'));
});

Breadcrumbs::for('testimonial', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Testimonial', url('/admin/testimonial'));
});

Breadcrumbs::for('campaign-add', function ($trail) {
    $trail->parent('campaigns');
    $trail->push('Add Campaign', url('/admin/campaign/add'));
});
Breadcrumbs::for('campaign-edit', function ($trail) {
    $trail->parent('campaigns');
    $trail->push('Edit Campaign', url('/admin/campaign/edit'));
});

Breadcrumbs::for('job-add', function ($trail) {
    $trail->parent('jobs');
    $trail->push('Add Job', url('/admin/campaign/add'));
});
Breadcrumbs::for('job-edit', function ($trail) {
    $trail->parent('jobs');
    $trail->push('Edit Job', url('/admin/campaign/edit'));
});

Breadcrumbs::for('campaign', function ($trail) {
    $trail->parent('campaigns');
    $campaign = \App\Models\Campaign::find(\Request::segment(3));
    $trail->push(@$campaign->title??'', url('admin/campaign/'.@$campaign['id'].'/jobs'));
});
Breadcrumbs::for('wallets', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('User Wallets', url('admin/wallets'));
});
Breadcrumbs::for('wallet', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('User Wallet Transaction', url('admin/wallets'));
});
Breadcrumbs::for('wallet-transactions', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('User Wallet Transaction', url('admin/wallets'));
});
Breadcrumbs::for('commission-wallet', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Admin Wallet Transaction', url('admin/wallets'));
});
Breadcrumbs::for('view-coupons-details', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('view-coupons-details', url('admin/view-coupons-details/{id}'));
});
