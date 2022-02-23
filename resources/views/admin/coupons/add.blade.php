{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

    <!--begin::Profile Overview-->
    <div class="d-flex flex-row">
        <!--begin::Aside-->

       
        <!-- End of view if -->

        <!--end::Aside-->
        <!--begin::Content-->
        <div class="flex-row-fluid ml-lg-8">
            @if(Request::segment(2) == "edit-coupon")
            <form action="{{url('admin/save-coupon').'/'.@$coupon['id']}}" method="post" id="coupon_form" enctype="multipart/form-data" autocomplete="off">
            @else
            <form action="{{url('admin/save-coupon')}}" method="post" id="coupon_form" enctype="multipart/form-data" autocomplete="off">
            @endif    
               @csrf
               <input type="hidden" name="id" value="{{@$coupon['id']}}">
                <!--begin::Advance Table: Widget 7-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">{{@$coupon['id'] ? "Edit" : "Add" }} Category</h3>
                                <!-- <span class="text-muted font-weight-bold font-size-sm mt-1">Update informaiton</span> -->
                        </div>
                        <div class="card-toolbar">
                            <input type="submit" id="coupon_form_submit" name="" class="btn btn-success mr-2" value="Save" />
                            <a href="{{url('admin/coupons')}}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                    <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mb-6">Coupon Details</h5>
                                </div>
                            </div>
                            
                            <div class="form-group row {{$errors->has('code') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Code</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="code" value="{{@$coupon->code?:old('code')}}" autocomplete="password_new" maxlength="20" />
                                </div>
                                @if ($errors->has('code'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="code" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('code') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('description') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Description</label>
                                <div class="col-lg-9 col-xl-6">
                                    <textarea class="form-control form-control-lg form-control-solid" name="description" value="{{@$coupon->description?:old('description')}}">{{@$coupon->description?:old('description')}}</textarea>
                                </div>
                                @if ($errors->has('description'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="description" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('description') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('type') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Type</label>
                                <div class="col-lg-9 col-xl-6">
                                    <select class="form-control form-control-lg form-control-solid" id="percentage_flat" name="type" >
                                        <option value="">Select type</option>
                                        <option value="percentage" {{@$coupon->type  == 'percentage' || old('type') == 'percentage' ?  'Selected' : ''}}>Percentage</option>
                                        <option value="flat" {{@$coupon->type  == 'flat' || old('type') == 'flat' ?  'Selected' : ''}}>Flat</option>
                                    </select>
                                </div>
                                @if ($errors->has('type'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="type" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('type') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('type') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Expiry Date</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group date" id="datetimepicker7" data-target-input="nearest">
                                        <input type="text" name="expiry_date" class="form-control datetimepicker-input" data-target="#datetimepicker7" value="{{@$coupon->expiry_date?:old('expiry_date')}}" readonly />
                                        <div class="input-group-append" data-target="#datetimepicker7" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('type'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="type" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('type') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('discount') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Discount</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid numeric" id="discount" type="number" name="discount" value="{{@$coupon->discount?:old('discount')}}" autocomplete="password_new" maxlength="20" />
                                @if ($errors->has('discount'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="discount" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('discount') }}
                                    </div>
                                </div>
                                @endif
                                </div>
                                <div class="discount"></div>
                            </div>

                            <div class="form-group row {{$errors->has('min_price') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Minimum Price</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid numeric" type="number" name="min_price" value="{{@$coupon->min_price?:old('min_price')}}" autocomplete="password_new" maxlength="20" />
                                @if ($errors->has('min_price'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="min_price" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('min_price') }}
                                    </div>
                                </div>
                                @endif
                                </div>
                            </div>

                            <div class="form-group row {{$errors->has('max_price') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Maximum Price</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid numeric" type="number" name="max_price" value="{{@$coupon->max_price?:old('max_price')}}" autocomplete="max_price" maxlength="20" />
                                @if ($errors->has('max_price'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="max_price" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('max_price') }}
                                    </div>
                                </div>
                                @endif
                                </div>
                            </div>
                            
                            

                        </div>
                        <!--end::Body-->
                </div>
                <!--end::Advance Table Widget 7-->
            </form>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Profile Overview-->

@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/pages/widgets.js') }}" type="text/javascript"></script>
@endsection


