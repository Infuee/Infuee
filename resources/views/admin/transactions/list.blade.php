{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Transactions
                    <!-- <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div> -->
                </h3>
            </div>
            
        </div>
        <div class="card-body">
            <!--begin: Search Form-->
            <!--begin::Search Form-->
            <div class="mb-7">
                @include('flash-message')
                
                <form action="{{url('admin/transactions')}}">
                
                <div class="row align-items-center">
                    <div class="col-lg-12 col-xl-12">
                        <div class="row align-items-center">
                            <div class="col-md-4 my-2 my-md-0">
                                <div class="d-flex align-items-center">
                                    <label class="mr-3 mb-0 d-none d-md-block">User Type:</label>
                                    <select class="form-control" name="user_type">
                                        <option value="">All</option>
                                        <option value="2" {{@$inputs['user_type'] == 2 ? 'selected' : ''}}>Influencer</option>
                                        <option value="1" {{@$inputs['user_type'] == 1 ? 'selected' : ''}}>User</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 my-2 my-md-0">
                                <div class="d-flex align-items-center">
                                    <label class="mr-3 mb-0 d-none d-md-block">Payment Type:</label>
                                    <select class="form-control" name="payment_type">
                                        <option value="">All</option>
                                        <option value="debit" {{@$inputs['payment_type'] == 'debit' ? 'selected' : ''}}>Debit</option>
                                        <option value="credit" {{@$inputs['payment_type'] == 'credit' ? 'selected' : ''}}>Credit</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 my-2 my-md-0">
                                <div class="d-flex align-items-center">
                                    <label class="mr-3 mb-0 d-none d-md-block">Payment Status:</label>
                                    <select class="form-control" name="payment_status">
                                        <option value="">All</option>
                                        <option value="paid" {{@$inputs['payment_status'] == 'paid' ? 'selected' : ''}}>Paid</option>
                                        <option value="pending" {{@$inputs['payment_status'] == 'pending' ? 'selected' : ''}}>Pending</option>                                        
                                        <option value="fail" {{@$inputs['payment_status'] == 'fail' ? 'selected' : ''}}>Fail</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-xl-1 mt-5 mt-lg-0 right-srch">
                                <input type="submit" name="" class="btn btn-light-primary px-6 font-weight-bold" value="Search">
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
                <table class="datatable datatable-bordered datatable-head-custom" id="transactions_data_listing">
                    <thead>
                    <tr>
                        <th title="ID">ID</th>
                        <th title="Order Id">Order Id</th>
                        <th title="Name">Name</th>
                        <th title="Email">Email</th>
                        <th title="Total">Total</th>
                        <th title="Discout">Discount</th>
                        <th title="Commission">Commission</th>
                        <th title="Influencer Paid Amount">Influencer Paid Amount</th>
                        <th title="Status">Status</th>
                        <th title="Transaction Number">Transaction Number</th>
                        <th title="Transaction Time">Transaction Time</th>
                        <th title="Type">Type</th>
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
