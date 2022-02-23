{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}
    <?php //echo '<pre>';print_r($orders[0]); die; 
        //$orders = $orders[0];
    ?>
    <!--begin::Profile Overview-->
    <div class="row">
        <div class="col-md-6 col-sm-6">
            <div class="d-flex flex-row order_detail_box height_box">
                <!--end::Aside-->
                <!--begin::Content-->
                <div class="flex-row-fluid ml-lg-8 detail_box">
                    <div class="card card-custom card-stretch">
                        <!--begin::Header-->
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Order Details</h3>
                            </div>
                        </div>
                        <table class="table">
                            <tr>
                                <td><b>Billing User Name:</b></td>
                                <td>{{@$orders->billing_first_name.' '.@$orders->billing_last_name}}</td>
                            </tr>
                            <tr>
                                <td><b>Description: </b></td>
                                <td>{!!@$orders->description!!}</td>
                            </tr>
                            <tr>
                                <td><b>Address: </b></td>
                                <td>{{@$orders->address.', '.@$orders->zipcode}}</td>
                            </tr>
                            <tr>
                                <td><b>Date Time: </b></td>
                                <td>{{@$orders->created_at}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!--end::Content-->
            </div>
        </div>
        <div class="col-md-6 cols-m-6">
            <div class="d-flex flex-row order_detail_box height_box">
                <div class="flex-row-fluid detail_box">
                    <div class="card card-custom card-stretch">
                        <!--begin::Header-->
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Payment Information</h3>
                            </div>
                        </div>
                        <table class="table">
                            @php
                            //echo '<pre>';print_r($orders->transactions); echo '</pre>';
                            @endphp
                            @if(empty(@$orders->transactions))
                            <tr>
                                <tr>No transaction generated yet.</tr>
                            </tr>
                            @else
                            <tr>
                                <td><b>Total Price:</b></td>
                                <td>{{env('CURRENCY').number_format(@$orders->total,2)}}</td>
                            </tr>
                            <tr>
                                <td><b>Discount Price:</b></td>
                                <td>{{env('CURRENCY')}}{{number_format(@$orders->discount_price, 2)}}</td>
                            </tr>
                            <tr>
                                <td><b>Transaction Number:</b></td>
                                <td>{{@$orders->transactions->transaction_no}}</td>
                            </tr>
                            <tr>
                                <td><b>Transaction Time:</b></td>
                                <td>{{@$orders->transactions->transaction_time}}</td>
                            </tr>
                            <tr>
                                <td><b>Status:</b></td>
                                <td>
                                    @if($orders->transactions->status == 1)
                                    Success
                                    @elseif(@$orders->transactions->status == 0)
                                    Pending
                                    @else
                                    {{@$orders->transactions->status}}
                                    @endif
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="d-flex flex-row order_detail_box">
        <div class="flex-row-fluid ml-lg-8 detail_box">
            <div class="card card-custom card-stretch">
                <!--begin::Header-->
                <div class="card-header py-3">
                    <div class="card-title align-items-start flex-column">
                        <h3 class="card-label font-weight-bolder text-dark">Order Items</h3>
                    </div>
                </div>
                <div class="row spacing_side"> 
                    @php 
                        $i = 1;
                    @endphp
                    @foreach($orders->order_items AS $order_items)
                    <div class="col-md-6 col-sm-6">
                        <table class="table table-bordered items_table">
                            
                            <tr>
                                <th colspan="2">Item <?=$i;?></th>
                                
                            </tr>
                            <tr>                        
                                <td><b>Plan Name:</b></td>
                                <td>{{@$order_items->userPlan->allPlan->name}}</td>                        
                            </tr>
                            <tr>                        
                                <td><b>Plan Price:</b></td>
                                <td>{{env('CURRENCY').@$order_items->userPlan->price}}</td>                        
                            </tr>
                            <tr>
                                <td><b>Plan Category:</b></td>
                                <td>{{@$order_items->userPlan->allPlan->allCategory->name}}</td>
                            </tr>
                            <tr>
                                <td><b>Influencer:</b></td>
                                <td>{{@$order_items->userPlan->allUser->first_name.' '.@$order_items->userPlan->allUser->last_name}}</td>
                            </tr>
                        </table>
                    </div>
                    @php 
                        $i++;
                    @endphp
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!--end::Profile Overview-->

@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/pages/widgets.js') }}" type="text/javascript"></script>
@endsection
