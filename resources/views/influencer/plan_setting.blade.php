{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')   

<div class="form_layout setting-plan bg-blue">
    <div class="plan-wrapper">
	    <div class="container">
	    	<div class="row">
	    		<div class="col-12 mx-auto section-space">
					<div class="signup_box manage_profile edit-profile_i common-form">
	    				<div class="plan-box">
	    					<form action="{{url('plan-setting')}}" method="post" id="plan-setting-form">
	    						<div class="plan-add">
	    							<a href="javascript:void" class="add_plan" data-bs-toggle="modal" data-bs-target="#planModal">+ Add plan</a>
								</div>	
	    						@csrf
		    					@foreach($categories as $category)
		    					@if(count($category['plansFront']))
		    					<h2>{{$category['name']}}</h2>
		    					<p class="text_music">Please enter your rate as per the length of the promotions</p>
		    					<div class="signup_form login_form row">
					    			@foreach($category['plansFront'] as $plan)
					    			@if(empty($plan['user_id']) || $plan['user_id'] == Auth::user()->id )
							    	<div class="col-md-6 col-md-6 form-group">
										<label for="inputEmail3" class="control-label">{{$plan['name']}}</label>
										<input type="number" placeholder="$ 00.00" class="form-control influencer-plans" name="price[{{$plan['id']}}]" value="{{$plan->getUserPrice(false)}}" maxlength="6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" required="">
										<small>({{$plan['description']}})</small>
										<div class="clearfix"></div>
									</div>
									@endif
									@endforeach
									<div class="clearfix"></div>
								</div>
								@endif
								@endforeach
	    						<div class="common-btns">
									<div class="cancel-btn">
										<a class="get_started cancel_btn flex-center" href="{{url('my-profile')}}">Cancel</a>
									</div>
	    							<div class="create-btn flex-center ms-auto">
										<input type="submit" name="" value="Submit" id="plan-setting-submit" class="get_started flex-center">
									</div>
	    						</div>
		    				</form>
	    				</div>
					</div>	
	    		</div>
	    	</div>
	    </div>
	</div>			
</div>

<!-- Modal -->
<div class="filter_modal">
	<div class="modal fade" id="planModal" role="dialog" aria-labelledby="myModalLabel">
	  	<div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
				  <h4 class="modal-title text-center" id="myModalLabel">Add Plan</h4>
				  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      	</div>
		      	<div class="modal-body">
		      		<div class="filter_form planing-form">
			      		<form action="{{url('custom-plan-setting').'/'.Auth::user()->id}}" id="custom_plan_setting" method="post">
			        		@csrf
			        		<div class="row">
								<div class="col-md-12">
									<div class="card-form_group mb-3">
										<label>Category:</label>
										<div class="form-group category-dropdown">
											<select class="category-dropdown-setup" name="category">
												@foreach($categories as $category)
												<option value="{{$category['id']}}">{{$category['name']}}</option>
												@endforeach
											</select>
											<!--<span class="icon-dropdown"><img src="../media/images/arrow-down.svg"></span>-->
										</div>
									</div>
								</div>
								<div class="col-md-12">
									<div class="card-form_group mb-3">
										<label>Name:</label>
	    								<div class="form-group">
	    									<input type="text" id="name" placeholder="Name" class="form-control" name="plan_name" value="">
	    								</div>
		    						</div>
								</div>
								<div class="col-md-12">
								 	<div class="card-form_group mb-3">
										<label>Description:</label>
	    								<div class="form-group">
	    									<textarea placeholder="Description" class="form-control" name="description" rows="3"></textarea>
	    								</div>
		    						</div>
								</div>
								<div class="col-md-12 text-center">
									<div class="view_result create-btn flex-center ms-auto">
										<input type="submit" name="" id="custom_plan_setting_submit" class="add-plan-submit" value="Submit"/>
									</div>
								</div>
							</div>
						</form>	
					</div>	      	
		      	</div>
		    </div>
	  	</div>
	</div>
</div>
@endsection