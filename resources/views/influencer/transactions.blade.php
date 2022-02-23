{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')

<div class="user_profile order_list transaction_box bg-blue">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 col-md-12 col-sm-12 mx-auto section-space">
				<div class="signup_box">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<!-- <i class="fa fa-exchange"></i> -->
							<h2 class="welcome_text"> Transactions </h2>
						</div>
						<div class="col-md-12 col-sm-12">
							<div class="influencer_order_list">
								<div class="table-responsive">
									<table class="table table-fixed table-striped border-collased">
										<thead>
											<tr>
												<th>ID</th>
												<th>Order</th>
												@if(!$isUser = auth()->user()->isUser())
												<th>User</th>
												<th>Plan/Price</th>
												@else
												<th>Price</th>
												<th>Discount</th>
												@endif
												<th>Status</th>
												<th>Time</th>
												<!-- <th>Actions</th> -->
											</tr>
										</thead>
										<tbody>
											@if($transactions)
											@foreach($transactions as $key => $transaction)
											<tr>
												<td class="color_theme">{{($transactions->currentpage()-1) * $transactions->perpage() + $key + 1}}</td>
												<td>
													{{$transaction['order']['order_id']}}
												</td>
												@if(!$isUser)
												<td>
													{!!$transaction->getUserInfluencer()!!}
												</td>
												@endif
												<td class="package color_blue">
													{{@$transaction['item']['userPlan']['plan']['name']}}
													<div class="price">
														<span>
															@if(0&&$transaction['order_item_id'])
															{{env('CURRENCY')}}{{number_format($transaction->getItemPrice(),2)}}
															@else
															{{env('CURRENCY')}}{{number_format($transaction['amount'] - $transaction['commision'],2)}}
															@endif</span>
													</div>
												</td>
												@if($isUser)
												<td class="package color_blue">
													<div class="price">
														<span>
															{{env('CURRENCY')}}{{number_format(($transaction['order']['total']-$transaction['amount']),2)}}
														</span>
													</div>
												</td>
												@endif
												<td>
													@if($transaction['status']==1)
													<span class="label label-success">Paid</span>
													@else
													<span class="label label-danger">Failed</span>
													@endif
												</td>
												<td>
													Date: {{date('d / M / Y', strtotime($transaction['created_at']))}}
												</td>
												<!-- <td>
			    												<a href="{{url('order')}}/{{\Helpers::encrypt($transaction['id'])}}" target="_blank">Order Details</a>
			    											</td>
			    											<td>
			    												<a href="{{url('download_pdf')}}/{{\Helpers::encrypt($transaction['id'])}}" target="_blank">Pdf</a>
			    											</td> -->
											</tr>
											@endforeach
											@else
											<tr>
												<td colspan="6" class="text-center">No transactions found</td>
											</tr>
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
	<div class="interest-wrapper lastsection-space_b">
		<div class="container-lg">
			@if(auth()->user()->isUser())
			<div class="row">
				<div class="col-lg-10 col-md-12 col-sm-12 mx-auto">
					<div class="become_an_influencer">
						<h2>Interested in becoming an influencer?</h2>
						<p>Do you have a following on Instagram? Do you want to make $ promoting brands and music? Fill out our quick application to potentially start selling promotions on Infuee!</p>
						<div class="prop-btns text-center">
                        <div class="accept-btn">
						<a href="{{url('be-influencer')}}" class="">Apply Now</a>
                        </div>

                    </div>
						
					</div>
				</div>
			</div>
			@endif
		</div>
	</div>
	</div>

	@endsection