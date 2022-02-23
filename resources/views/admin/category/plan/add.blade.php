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
            <form action="{{url('admin/plan-category')}}/{{@$category['id']}}/save" method="post" id="category_plan_form" enctype="multipart/form-data" autocomplete="off">
               @csrf
               <input type="hidden" name="id" value="{{@$plan['id']}}">
                <!--begin::Advance Table: Widget 7-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label">{{@$category['name']}} {!! @$category->statusBadge() !!} </h3>
                            <h3 class="card-label font-weight-bolder text-dark">{{@$plan['id'] ? "Edit" : "Add" }} Plan</h3>
                                <!-- <span class="text-muted font-weight-bold font-size-sm mt-1">Update informaiton</span> -->
                        </div>
                        <div class="card-toolbar">
                            <input type="submit" id="category_plan_submit" name="" class="btn btn-success mr-2" value="Save" />
                            <a href="{{url('admin/plan-category/')}}/{{@$category['id']}}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                    <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mb-6">Plan Details</h5>
                                </div>
                            </div>
                            
                            <div class="form-group row {{$errors->has('name') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Name</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="name" value="{{old('name')?:@$plan->name}}" autocomplete="password_new" placeholder="Plan Name"  maxlength="20" />
                                </div>
                                @if ($errors->has('name'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="name" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('name') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="form-group row {{$errors->has('name') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Description</label>
                                <div class="col-lg-9 col-xl-6">
                                    <textarea class="form-control form-control-lg form-control-solid" name="description" rows="3" placeholder="Short Description Here..." maxlength="100">{{old('description')?:@$plan->description}}</textarea>
                                </div>
                                @if ($errors->has('description'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="description" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('description') }}
                                    </div>
                                </div>
                                @endif
                            </div> 

                            <!-- <div class="form-group row {{$errors->has('commission') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Commission (%)</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input type="number" placeholder="Commission" name="commission" class="form-control form-control-lg form-control-solid numeric" data-maxlength="100" data-minlength="1"  value="{{old('commission')?:@$plan->commission}}">
                                </div>
                                @if ($errors->has('description'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="description" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('description') }}
                                    </div>
                                </div>
                                @endif
                            </div> -->
                            @if(@$plan['id'])
                                <div class="form-group row {{$errors->has('status') ? 'has-danger' : ''}}">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Status</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control form-control-lg form-control-solid" name="status">
                                            @foreach($plan->status() as $key => $status)
                                                <option value="{{$key}}" {{$plan['status'] == $key ? "selected" : ""}}>{{$status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('status'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="status" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('status') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endif

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


