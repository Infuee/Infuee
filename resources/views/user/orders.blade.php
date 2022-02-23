{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')   

<div class="user_profile form_layout order_list">
	    		<div class="container">
	    			<div class="row">
	    				<div class="col-sm-12 col-md-12">
	    					<div class="signup_box">
	    						<div class="row">
	    							<div class="col-md-12 col-sm-12">
	    								<h2 class="welcome_text">Welcome, Alex John </h2>
	    							</div>
	    							<div class="col-md-12 col-sm-12">
	    								<div class="influencer_order_list">
	    									<div class="table-responsive">
			    								<table class="table">
			    									<thead>
			    										<tr>
			    											<th>ID</th>
			    											<th>Name</th>
			    											<th>Email</th>
			    											<th>Package</th>
			    											<th>Category</th>
			    											<th>Time</th>
			    											<th>Status</th>
			    										</tr>
			    									</thead>
			    									<tbody>
			    										<tr>
			    											<td class="color_theme">01</td>
			    											<td>
			    												<span><img src="images/table_img.png"> <span class="user_name">Angelian </span></span>
			    											</td>
			    											<td class="color_blue">Angelian@gmail.com</td>
			    											<td class="package color_blue">Basic Music Promotion 
			    												<div class="price">
									    							<span>$400</span>
									    						</div>
									    					</td>
									    					<td class="color_theme category_block">Music</td>
									    					<td class="date_block">Date: 12/ Sep / 2020</td>
									    					<td>
									    						<a href="" class="accept_btn color_green">Accept</a>
									    						<a href="" class="accept_btn color_red">Decline</a>
									    						<!-- <a href="" class="accept_btn color_green accepted">Accepted</a>
									    						<a href="" class="accept_btn color_red rejected">Declined</a> -->
									    					</td>
			    										</tr>
			    										<tr>
			    											<td class="color_theme">02</td>
			    											<td>
			    												<span><img src="images/table_img.png"> <span class="user_name">Angelian </span></span>
			    											</td>
			    											<td class="color_blue">Angelian@gmail.com</td>
			    											<td class="package color_blue">Basic Music Promotion 
			    												<div class="price">
									    							<span>$400</span>
									    						</div>
									    					</td>
									    					<td class="color_theme category_block">Music</td>
									    					<td class="date_block">Date: 12/ Sep / 2020</td>
									    					<td>
									    						<a href="" class="accept_btn color_green">Accept</a>
									    						<a href="" class="accept_btn color_red">Decline</a>
									    					</td>
			    										</tr>
			    										<tr>
			    											<td class="color_theme">03</td>
			    											<td>
			    												<span><img src="images/table_img.png"> <span class="user_name">Angelian </span></span>
			    											</td>
			    											<td class="color_blue">Angelian@gmail.com</td>
			    											<td class="package color_blue">Basic Music Promotion 
			    												<div class="price">
									    							<span>$400</span>
									    						</div>
									    					</td>
									    					<td class="color_theme category_block">Music</td>
									    					<td class="date_block">Date: 12/ Sep / 2020</td>
									    					<td>
									    						<a href="" class="accept_btn color_green">Accept</a>
									    						<a href="" class="accept_btn color_red">Decline</a>
									    					</td>
			    										</tr>
			    										<tr>
			    											<td class="color_theme">04</td>
			    											<td>
			    												<span><img src="images/table_img.png"> <span class="user_name">Angelian </span></span>
			    											</td>
			    											<td class="color_blue">Angelian@gmail.com</td>
			    											<td class="package color_blue">Basic Music Promotion 
			    												<div class="price">
									    							<span>$400</span>
									    						</div>
									    					</td>
									    					<td class="color_theme category_block">Music</td>
									    					<td class="date_block">Date: 12/ Sep / 2020</td>
									    					<td>
									    						<a href="" class="accept_btn color_green">Accept</a>
									    						<a href="" class="accept_btn color_red">Decline</a>
									    					</td>
			    										</tr>
			    										<tr>
			    											<td class="color_theme">05</td>
			    											<td>
			    												<span><img src="images/table_img.png"> <span class="user_name">Angelian </span></span>
			    											</td>
			    											<td class="color_blue">Angelian@gmail.com</td>
			    											<td class="package color_blue">Basic Music Promotion 
			    												<div class="price">
									    							<span>$400</span>
									    						</div>
									    					</td>
									    					<td class="color_theme category_block">Music</td>
									    					<td class="date_block">Date: 12/ Sep / 2020</td>
									    					<td>
									    						<a href="" class="accept_btn color_green">Accept</a>
									    						<a href="" class="accept_btn color_red">Decline</a>
									    					</td>
			    										</tr>
			    									</tbody>
			    								</table>
			    							</div>
		    							</div>
	    							</div>
	    						</div>
	    					</div>
	    				</div>
	    			</div>
	    			<div class="row">
	    				<div class="col-md-10  col-md-offset-1 col-sm-10 col-sm-offset-1">
	    					<div class="become_an_influencer">
	    						<h2>Interested in becoming an influencer?</h2>
	    						<p>Do you have a following on Instagram? Do you want to make $ promoting brands and music? Fill out our quick application to potentially start selling promotions on Infuee!</p>
	    						<div class="text-center">
    								<a href="" class="get_started">Apply Now</a>
    							</div>
	    					</div>
	    				</div>
	    			</div>
	    		</div>
	    	</div>

@endsection