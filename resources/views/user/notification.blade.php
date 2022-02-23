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
	    								<h2 class="welcome_text"> Notification</h2>
	    								

	    								<a href="javascript:void(0)" class="clear-notification" title="Clear Notifications" style="float: right;" item="notification" url="{{ url('notification/clear') }}" operation="clear"> Clear Notification </a>
	    							</div>
	    							<div class="col-md-12 col-sm-12">
	    								<div class="influencer_order_list">
	    									<div class="table-responsive">
			    								<table class="table table-fixed table-striped border-collased">
			    									<thead>
			    										<tr>
			    											<th>ID</th>
			    											<th>Notifications</th>
			    											<th>Delete </th>
			    											
			    										</tr>
			    									</thead>
			    									<tbody>
			    										<tr><?php $i = '1';
			    											foreach($getAllNotificatios as $getAllNotificatio){ ?>
			    											<td class="color_theme"><?php echo $i; ?>  </td>
			    											<td class="color_blue">{!! $getAllNotificatio['notification'] !!}</td>
			    											<td><span> <a href="javascript:void(0)" class="delete-notification" item="Notification" url="{{ url('notification/'.$getAllNotificatio['id'].'/delete') }}" operation="delete"> <i class="far fa fa-times"></i> </a></span></td>
			    											</tr>
			    											<?php $i++;	} ?>
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
	    		</div>
	    	</div>
    <!-- Modal -->
<div class="filter_modal">
	<div class="modal fade" id="order-details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  	<div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h4 class="modal-title text-center" id="myModalLabel">Order Instructions</h4>
		      	</div>
		      	<div class="modal-body">
		        	<p id="order-details-instructions"></p>
		        </div>
		    </div>
	  	</div>
	</div>
</div>
@endsection