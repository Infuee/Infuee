{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')   

<div class="user_profile order_list bg-blue transaction_box lastsection-space_b">
	    		<div class="container">
	    			<div class="row">
	    				<div class="col-lg-10 col-md-12 col-sm-12 mx-auto section-space">
	    					<div class="signup_box">
	    						<div class="row">
	    							<div class="col-md-12 col-sm-12">
	    								<h2 class="welcome_text"><i class="fa fa-first-order"></i> Orders</h2>
	    							</div>
	    							<div class="col-md-12 col-sm-12">
	    								<div class="influencer_order_list">
	    									<div class="table-responsive">
			    								<table class="table table-fixed table-striped border-collased">
			    									<thead>
			    										<tr>
			    											<th>ID</th>
			    											<th>Name</th>
			    											<th>Email</th>
			    											<th>Package</th>
			    											<th>Category</th>
			    											<th>Order ID</th>
			    											<th>Order Instructions</th>
			    											<th>Save files and caption</th>
			    											<th>Time</th>
			    											<th>Status</th>
			    											<th>Mark Done</th>
			    										</tr>
			    									</thead>
			    									<tbody>
			    										@if($orderItems)
			    										@foreach($orderItems as $key => $item)
			    												@php

			    												$customer = $item->getUser();
			    												if(empty($customer)){
			    													continue;
			    												}
			    												@endphp
			    										<tr>
			    											<td class="color_theme">{{($orderItems->currentpage()-1) * $orderItems->perpage() + $key + 1}}</td>
			    											<td>
			    												<span>
			    													@if(is_file(public_path("/media/users").'/'.@$customer['image']))
							                                            <img src="{{asset('media/users/'.$customer['image'])}}" style="width: 30px;">
							                                        @else
							                                            <img src="media/users/blank.png" style="width: 30px;">
							                                        @endif</a>
			    													<span class="user_name">{{$customer['first_name']}} {{$customer['last_name']}}</span></span>
			    											</td>
			    											<td class="color_blue">{{$customer['email']}}</td>
			    											<td class="package color_blue">{{$item['userPlan']['plan']['name']}}  
			    												<div class="price">
									    							<span>${{number_format(@$item['userPlan']['price'],2)}}</span>
									    						</div>
									    					</td>
									    					<td class="color_theme category_block">{{$item['userPlan']['plan']['category']['name']}}</td>
									    					<td class="color_theme category_block">{{$item['order']['order_id']}}</td>
									    					<td class="date_block">
									    						<a href="javascript:void(0)" class="accept_btn order-details" data-details="{{$item->getOrderIns()}}">View</a>
									    					</td>
									    					<td>
									    						<a href="{{ url('order-download-zip/'.$item['order']['order_id']) }}" class="" data-item="{{\Helpers::encrypt($item['id'])}}">Save files and caption</a>
									    					</td>
									    					<td class="date_block">Date: {{date('d / M / Y', strtotime($item['created_at']))}}</td>
									    					<td>
									    						@if($item['status'] == 0)
									    						<a href="javascript:void(0)" class="accept_btn accept_order" data-item="{{\Helpers::encrypt($item['id'])}}">Accept</a>
									    						@else
									    						<a href="javascript:void(0)" class="accept_btn color_green" >Accepted</a>
									    						@endif
									    						<!-- <a href="" class="accept_btn color_red">Decline</a> -->
									    					</td>									    					
									    					<td>
									    						@if($item['status'] == 1)
									    							@php
									    								if($item['mark_done'] == 1){
									    									$checked = 'checked';
									    								}else{
									    									$checked = '';
									    								}
									    							@endphp

									    							<div class="form-group-checkbox">
																      	<input type="checkbox" name="" class="mark_done" data-id="{{$item['id']}}" id="mark{{$item['id']}}" {{$checked}} >
																      	<label for="mark{{$item['id']}}"></label>
																    </div>
									    						@endif
									    					</td>
									    					
			    										</tr>
			    										@endforeach
			    										@endif
			    									</tbody>
			    								</table>
			    							</div>
		    							</div>
	    							</div>
	    						</div>
	    					</div>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
    <!-- Modal -->
<div class="filter_modal orders_modal">
	<div class="modal fade" id="order-details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  	<div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
			        <h4 class="modal-title text-center" id="myModalLabel">Order Instructions</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      	</div>
		      	<div class="modal-body">
		        	<div id="order-details-instructions"> </div>
		        </div>
		    </div>
	  	</div>
	</div>
</div>
@endsection

