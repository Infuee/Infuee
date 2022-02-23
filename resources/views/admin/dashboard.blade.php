{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

 
    <div class="row">
        <div class="col-lg-6">
            @include('admin.graphs.stats', ['class' => 'card-stretch gutter-b'])
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-sm-4 offset-sm-8">
                    <div class="form-group">
                        <form id="dashboard_filter_from">
                            <select class="form-control js-example-basic-single" name="type" id="dashboard_filter_type">
                                <option value="1" {{$type == 1 ? 'selected':''}}>Current Month</option>
                                <option value="2" {{$type == 2 ? 'selected':''}}>Last Week</option>
                                <option value="3" {{$type == 3 ? 'selected':''}}>Last Month</option>
                                <option value="4" {{$type == 4 ? 'selected':''}}>Last Six Month</option>
                                <option value="5" {{$type == 5 ? 'selected':''}}>Last Year</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            @include('admin.graphs.orders', ['class' => 'card-stretch card-stretch-half gutter-b'])
            @include('admin.graphs.commision', ['class' => 'card-stretch card-stretch-half gutter-b'])
        </div>
        <?php /*
        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">
            @include('pages.widgets._widget-5', ['class' => 'card-stretch gutter-b'])
        </div>

        <div class="col-xxl-8 order-2 order-xxl-1">
            @include('pages.widgets._widget-6', ['class' => 'card-stretch gutter-b'])
        </div>

        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">
            @include('pages.widgets._widget-7', ['class' => 'card-stretch gutter-b'])
        </div>

        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">
            @include('pages.widgets._widget-8', ['class' => 'card-stretch gutter-b'])
        </div>

        <div class="col-lg-12 col-xxl-4 order-1 order-xxl-2">
            @include('pages.widgets._widget-9', ['class' => 'card-stretch gutter-b'])
        </div>  */ ?>
    </div>

@endsection

{{-- Scripts Section --}}
@section('scripts')
<script type="text/javascript">
    orders_data = [{{ implode(",", $orders_['data']) }}];
    orders_category = ['{!! implode("','", $orders_["category"]) !!}'];
    commision_data = [{{ implode(",", $commision['data']) }}];
    commision_category = ['{!! implode("','", $commision["category"]) !!}'];
</script>


    <script src="{{ asset('js/graphs.js') }}" type="text/javascript"></script>
@endsection
