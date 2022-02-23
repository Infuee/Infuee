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
            <form action="{{url('admin/save-manage-category')}}" method="post" id="category_form" enctype="multipart/form-data" autocomplete="off">
               @csrf
               <input type="hidden" name="id" value="{{@$category['id']}}">
                <!--begin::Advance Table: Widget 7-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">{{@$category['id'] ? "Edit" : "Add" }} Category</h3>
                                <!-- <span class="text-muted font-weight-bold font-size-sm mt-1">Update informaiton</span> -->
                        </div>
                        <div class="card-toolbar">
                            <input type="submit" id="category_submit" name="" class="btn btn-success mr-2" value="Save" />
                            <a href="{{url('admin/manage-categories')}}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                    <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mb-6">Category Details</h5>
                                </div>
                            </div>
                            
                            <div class="form-group row {{$errors->has('name') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Name</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="name" value="{{@$category->name?:old('name')}}" autocomplete="password_new" maxlength="20" />
                                </div>
                                @if ($errors->has('name'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="name" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('name') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="form-group row {{$errors->has('image') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Category Icon</label>
                                <div class="col-lg-9 col-xl-6">
                                    <input type="file" name="image" data-image="logoRemove" id="input-file-now" class="dropify" accept="image/x-png,image/jpeg" @if(@$category['image'] == '') @else data-default-file="{{ asset('uploads/category/'.@$category['image']) }}" @endif/>
                                    <input type="hidden" value="@if(@$category['image'] =='')nofile @else{{@$category['image']}}@endif" name="logoRemove" id="logoRemove">
                                    <span class="help-block"></span>
                                </div>
                                @if ($errors->has('image'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="image" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('image') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            @if(empty(@$category['id']))
                            <div class="form-group row {{$errors->has('categories') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Social Platforms</label>
                                <div class="col-lg-9 col-xl-6">
                                    <select class="form-control form-control-lg form-control-solid" name="platform_id[]" multiple>
                                        @foreach(@$platforms as $key => $platform)
                                            <option value="{{@$platform['id']}}" >{{$platform['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            @if(@$category['id'])
                                <div class="form-group row {{$errors->has('status') ? 'has-danger' : ''}}">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Status</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control form-control-lg form-control-solid" name="status">
                                            @foreach($category->status() as $key => $status)
                                                <option value="{{$key}}" {{$category['status'] == $key ? "selected" : ""}}>{{$status}}</option>
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


