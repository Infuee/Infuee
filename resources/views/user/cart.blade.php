{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')

<div class="user_profile cart_box bg-blue lastsection-space_b section-space cart-main">
	<div class="container-lg">
		<div class="row">
			<div class="col-sm-12 col-md-12">
			
				<div class="signup_box influencer_account">
					<form action="{{url('placeorder')}}" id="place_order_form" method="post" enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="selected_card" id="selected_card" value="{{old('selected_card')}}">
						
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<h2 class="text-center welcome_text">Review My Order </h2>
								<p class="review_tagline">Want to add more influencers? Click below to browse available influencers.</p>
							</div>
						</div>
						<div class="row">
							 @if( Helpers::isWebview() )
						          <div class="col-md-8 col-sm-12 text-right xs-text-left">
									<a href="{{url('browse')}}" class="link_browse"><img src="media/images/left-arrow_blue.png"> Browse Influencers</a>
								</div>
						      @else 
								<div class="col-md-8 col-sm-12 text-right xs-text-left">
									<a href="{{url('influencers')}}" class="link_browse"><img src="media/images/left-arrow_blue.png"> Browse Influencers</a>
								</div>
							@endif
							<div class="col-md-4 col-sm-4"></div>
						</div>
						@if(@$cart['items'] && count($cart['items']))
						<div class="row">
							<div class="col-md-8 col-sm-12">
								@php
								$totalAmount = 0;
								@endphp

								@foreach($cart['items'] as $item)
								@php
								$totalAmount = $totalAmount + $item['userPlan']['price'];
								@endphp
								<div class="order_listing">
									<div class="row">
										<div class="col-md-2 col-sm-2">
											<div class="user_img">
												@if(is_file(public_path("/media/users").'/'.@$item['userPlan']['influencer']['image']))
												<img src="{{asset('media/users/'.$item['userPlan']['influencer']['image'])}}">
												@else
												<img src="media/users/blank.png">
												@endif</a>
											</div>
										</div>
										<div class="col-md-9 col-sm-8">
											<div class="position-relative">
												<div class="delete_icon responsive_icon">
													<a href="javascript:void(0)" class="remove_cart" data-planid="{{\Helpers::encrypt($item['id'])}}"><img src="media/images/trash1.png"></a>
												</div>
											</div>
											<div class="order_detail">
												<h2>{{$item['userPlan']['plan']['name']}} {{$item['userPlan']['plan']['category']['name']}} with <a href="{{url('influencer')}}/{{$item['userPlan']['influencer']['username']?$item['userPlan']['influencer']['username']:\Helpers::encrypt($item['userPlan']['user_id'])}}/profile">{{'@'.$item['userPlan']['influencer']['first_name']}}</a></h2>
												<a href="javascript:void(0)" class="price_btn">${{number_format(@$item['userPlan']['price'],2)}}</a>
												<h4>{{@$item['userPlan']['influencer']->getFollowers()}} Followers</h4>
											</div>
										</div>
										<div class="col-md-1 col-sm-2 text-left text-md-end">
											<div class="delete_icon large_screen text-end">
												<a href="javascript:void(0)" class="remove_cart" data-planid="{{\Helpers::encrypt($item['id'])}}"><img src="media/images/trash1.png"></a>
											</div>
										</div>
									</div>
								</div>
								@endforeach
							</div>
							<div class="col-md-4 col-sm-12">
								<div class="order_summary_block">
									<h3>Order Summary</h3>
									<!-- <p>Cart #HBZ2QBPRB3333</p> -->
									<hr class="border_line">
									@if(!@$cart['coupon'])
									<p class="coupon_tagline">Have a coupon? Enter it below</p>
									<div class="coupon_code_input">
										<input type="text" id="coupon_code" placeholder="Coupon Code" class="form-control">
										<span class="code-error-msg" style="color: red;"></span>
									</div>
									<div class="apply_discount">
										<a href="javascript:void(0)" class="btn blue-btn" id="apply-coupon">Apply Discount</a>
									</div>
									@else
									<p class="coupon_tagline pull-left"><span class="apply_coupon">Applied Coupon :</span> <strong>{{@$cart['coupon']['coupon']['code']}}</strong>
										<input type="hidden" value="{{@$cart['coupon']['coupon']['id']}}" name="coupon_id">
									</p>
									<div class="coupon_code_input pull-right">
										<a href="javascript:void(0)" class="remove_coupon" data-couponid="{{\Helpers::encrypt(@$cart['coupon']['id'])}}"><img src="media/images/trash.png"></a>
									</div>
									<div class="clearfix"></div>
									@endif
									<hr class="border_line">
									<div class="sub_total text-right">
										<p><span style="color: #222">Subtotal</span> <span class="price_text">${{number_format($totalAmount,2)}}</span></p>
										@php
										$totalAmount = $totalAmount;
										@endphp
										@if(@$cart['coupon'])
										<p>
											<!-- <span>{{@$cart['coupon']['coupon']['code']}}</span> -->
											<span>Discount</span>
											<span class="price_text">- ${{number_format(@$cart['coupon']['amount'],2)}}</span>
										</p>
										@php
										$totalAmount = $totalAmount - @$cart['coupon']['amount'];
										@endphp
										@endif

										<p><span style="color: #222">Total</span> <span class="price_text">${{number_format($totalAmount,2)}}</span></p>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<h2 class="text-center order_inst welcome_text">Order Instructions</h2>
								<p class="text-left tagline_order">Enter instructions for your order below</p>
							</div>
						</div>
						<div class="error_block">
							<p> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> For all music promotions, please provide a link to the Instagram sound and address where you would like the influencer to begin the video </p>
						</div>
						<div class="text_block form-group">
							<textarea id="" name="description" class="form-control ckeditor" placeholder="Add the link to your song, instructions for your brand, etc.">{{old('description')}}</textarea>
							@if ($errors->has('description'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="description" data-validator="notEmpty" class="fv-help-block" style="color: red;">
                                        {{ $errors->first('description') }}
                                    </div>
                                </div>
                            @endif
						</div>
						<div class="text_block form-group">
							<label>Caption</label>
							<textarea id="" name="caption" class="form-control ckeditor" placeholder="Caption.">{{old('caption')}}</textarea>
							@if ($errors->has('caption'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="caption" data-validator="notEmpty" class="fv-help-block" style="color: red;">
                                        {{ $errors->first('caption') }}
                                    </div>
                                </div>
                            @endif
						</div>
						<div class="text_block form-group">
							<label for="forlabel5">Image/Video <span class="required">*</span></label>
                                <!-- <div class='file-input'> -->
                                    <input type="file" name="image_video" data-image="logoRemove" id="file" class="dropify" accept="image/*,video/*">
                                    <span id="file_error"></span>
                                    
                                <!-- </div> -->
                            @if ($errors->has('image_video'))
                            <div class="fv-plugins-message-container">
                                <div data-field="image_video" data-validator="notEmpty" class="fv-help-block" style="color:red;">
                                    {{ $errors->first('image_video') }}
                                </div>
                            </div>
                            @endif
						</div>
						
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<h2 class="text-center order_inst welcome_text">Payment</h2>
								<p class="text-left tagline_order">After submitting payment, you will be redirected to your orders page where you will be able to view the status for this order and receive updates.</p>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<h3 class="billing_heading">Billing Information</h3>
							</div>
							<div class="form_payment common-form">
								<div class="row">
									<div class="col-md-6 col-sm-6">
										<div class="form-group common-form_group">
											<input type="text" placeholder="First Name" class="form-control" name="first_name" value="{{old('first_name')?:$user['first_name']}}">
										</div>
									</div>
									<div class="col-md-6 col-sm-6">
										<div class="form-group  common-form_group">
											<input type="text" placeholder="Last Name" class="form-control" name="last_name" value="{{old('last_name')?:$user['last_name']}}">
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-12 col-sm-12">
										<div class="form-group  common-form_group">
											<input type="text" id="address" placeholder="Address" class="form-control" name="address" value="{{old('address')?:$user['address']}}">
											<input type="hidden" id="city" placeholder="City" class="form-control" name="city" value="{{old('city')?:$user['city']}}">
											<input type="hidden" id="state" placeholder="State" class="form-control" name="state" value="{{old('state')?:$user['state']}}">
											<input type="hidden" id="zip" placeholder="Zip/Postal" class="form-control" name="zip" value="{{old('zip')?:@$user['zip']}}">

											<input type="hidden" id="country" name="country" value="{{old('country')}}">
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
							<div class="col-md-12 col-sm-12">
								<h3 class="billing_heading responsive_space">Payment Information</h3>
							</div>
							<div class="clearfix"></div>
							@if(count($cards))
							@foreach($cards as $card)
							<div class="saved_card">
								<div class="row">
									<div class="col-md-8 col-sm-8">
										<div class="">
											<label class="container_radio"> xxxx xxxx xxxx ending in {{$card['last4']}}
												<input type="radio" name="s_card" value="{{$card['id']}}" class="radio-card" checked>
												<span class="checkmark1"></span>
											</label>
											<p class="card_name">{{$card['name']}}</p>
											<p class="card_name">{{sprintf('%02d', $card['exp_month'])}}/{{$card['exp_year']}}</p>
										</div>
									</div>
									<div class="col-md-4 col-sm-4">
										<p class="delete text-end"><a class="delete_box remove_card" href="javascript:void(0)" data-id="{{$card['id']}}"><i class="far fa-trash-alt"></i></a></p>
									</div>
								</div>
							</div>
							@endforeach
							@endif
						
							<div class="form_payment common-form addcard-form">
								<div class="row">
									<div class="col-md-2 col-sm-2">
										<div class="form-group Pos-R common-form_group">
											<label class="container_radio"> New Card
												<input type="radio" name="s_card" value="" class="" {{count($cards)?:"checked"}}>
												<span class="checkmark1"></span>
											</label>
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-6 col-sm-6">
										<div class="form-group Pos-R common-form_group">
											<input placeholder="Card Number" class="form-control" type="text" maxlength="19" size="16" name="ccnum" value="" id="ccnum">
											<!-- <div><small>type: <strong id="ccnum-type">invalid</strong></small></div> -->
										</div>
									</div>
									<div class="col-md-3 col-sm-3">
										<div class="form-group common-form_group">
											<div class="Pos-R">
												<input name="expiry" placeholder="** / ****" size="7" type="tel" name="expiry" value="" id="expiry" class="form-control">
											</div>
										</div>
									</div>
									<div class="col-md-3 col-sm-3">
										<div class="form-group common-form_group">
											<input placeholder="***" size="4" type="tel" name="cvc" value="" id="cvc" class="form-control">
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="col-md-12 col-sm-12">

										<!-- <p class="payment_text"><input id="tnc" type="checkbox" name="tnc"/></p>
			    								<label for="tnc" class="payment_text"></label> -->


										<label class="container_checkbox"> 
											<input id="tnc" type="checkbox" name="tnc" />
											<span class="checkmark2"></span>
											By placing order, you agree to the <a href="{{url('terms-of-service')}}" target="_blank">TERMS OF SERVICE</a> and <a href="{{url('privacy-policy')}}" target="_blank">PRIVACY POLICY</a> (All Payments Are Secure)
											
										</label>

									</div>
								</div>
							</div>
						</div>
						<div class="text-center payment_submit">
							<button type="submit" class="blue-btn placeorder_btn" id="place_order_submit">Place Order</button>
						</div>
						@else

						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="text-center emptyCart-outer">
								<img src="media/images/empty-cart.png" alt=""> 
								<h2 class="text-center">Your cart is empty.</h2>
							</div>
							</div>
						</div>

						@endif
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
