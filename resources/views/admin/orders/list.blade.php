{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Orders
                    <!-- <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div> -->
                </h3>
            </div>
            
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <!--begin::Search Form-->
            <div class="mb-7">
                @include('flash-message')
                <form action="{{url('admin/orders')}}">
                
                <div class="row align-items-center">
                    <div class="col-lg-12 col-xl-12">
                        <div class="row align-items-center">
                            <div class="col-lg-12 col-xl-12 mt-5 mt-lg-0 links">
                                <span>
                                    <a id="today">Today</a>
                                    <a id="current_week">Week</a>
                                    <a id="current_month">Month</a>
                                    <a id="current_year">Year</a>
                                </span>
                            </div>
                            <!-- <div class="col-md-4 my-2 my-md-0">
                                <div class="d-flex align-items-center form-group">
                                    <label class="mr-3 mb-0 d-none d-md-block">Categories:</label>
                                    <select class="form-control" name="category">
                                        <option value="">All Categories</option>
                                        @foreach($PlanCategories AS $PlanCategory)
                                        <option value="{{$PlanCategory->id}}" {{@$inputs['category'] == $PlanCategory->id ? 'selected' : ''}} >{{$PlanCategory->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-4 my-2 my-md-0">
                                <div class="d-flex align-items-center form-group position-relative">
                                    <label class="mr-3 mb-0 d-none d-md-block">From</label>
                                    <input type="text" class="form-control created_at" name="from" id="from" placeholder="From" autocomplete="off" value="{{@$inputs['from']}}">
                                    <div class="icon_calendar">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 my-2 my-md-0">
                                <div class="d-flex align-items-center form-group position-relative">
                                    <label class="mr-3 mb-0 d-none d-md-block">To</label>
                                    <input type="text" class="form-control created_at" name="to" id="to" placeholder="To" autocomplete="off" value="{{@$inputs['to']}}">
                                    <div class="icon_calendar">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-xl-3 mt-5 mt-lg-0">
                                <input type="submit" name="" class="btn btn-light-primary px-6 font-weight-bold search_btn" value="Search">
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
            <!--end::Search Form-->
            <!--end: Search Form-->
            <!--begin: Datatable-->
            <div class="table-responsive">
                <table class="datatable datatable-bordered datatable-head-custom" id="orders_data_listing">
                    <thead>
                    <tr>
                        <th title="ID">ID</th>
                        <th title="Order Id sdfsdf">Order Id</th>
                        <th title="Name">Name</th>
                        <th title="Items">Items</th>
                        <th title="Billing User Name">Billing User Name</th>
                        <th title="Address">Address</th>
                        <th title="created_at">Created At</th>
                        <th title="Action">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!--end: Datatable-->
        </div>
    </div>

@endsection

{{-- Styles Section --}}
@section('styles')

@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/pages/crud/ktdatatable/base/html-table.js') }}" type="text/javascript"></script>
@endsection
