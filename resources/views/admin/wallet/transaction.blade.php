{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">User Wallet Transaction
                    <!-- <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div> -->
                </h3>
            </div>
            <div class="card-toolbar">
                
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-2">
                    <a href="{{ url('admin/wallet/transactions?type=user') }}" class="btn btn-primary font-weight-bolder {{ $type == 'user' ? 'active' : '' }}">
                    User</a>
                </div>
                <div class="col-2">
                    <a href="{{ url('admin/wallet/transactions?type=influencer') }}" class="btn btn-primary font-weight-bolder {{ $type == 'influencer' ? 'active' : '' }}">
                    Influencer</a>
                </div>
                <div class="col-2">
                    <a href="{{ url('admin/wallet/transactions?type=admin') }}" class="btn btn-primary font-weight-bolder {{ $type == 'admin' ? 'active' : '' }}">
                    Admin</a>
                </div>
            </div>
            <!--begin: Search Form-->
            <!--begin::Search Form-->
            <div class="mb-7">
                @include('flash-message')

            </div>
            <!--end::Search Form-->
            <!--end: Search Form-->
            <!--begin: Datatable-->
            <div class="table-responsive">
                <table class="datatable datatable-bordered datatable-head-custom" id="wallet_transaction_data_listing">

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
