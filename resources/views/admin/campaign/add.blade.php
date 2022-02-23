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
            <form action="{{url('admin/campaign/save')}}" method="post" id="{{@$campaign['id'] ? 'campaign_form_edit' : 'campaign_form'}}" enctype="multipart/form-data" autocomplete="off">
               @csrf
               <input type="hidden" name="id" id='campaign_id' value="{{@$campaign['id']}}">
                <!--begin::Advance Table: Widget 7-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">{{@$campaign['id'] ? "Edit" : "Add" }} Campaign</h3>
                                <!-- <span class="text-muted font-weight-bold font-size-sm mt-1">Update informaiton</span> -->
                        </div>
                        <div class="card-toolbar">
                            <input type="submit" id="campaign_form_submit" name="" class="btn btn-success mr-2" value="Save" />
                            <a href="{{url('admin/campaigns')}}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                    <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mb-6">Campaign Details</h5>
                                </div>
                            </div>
                             <div class="form-group row {{$errors->has('category') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Users/Influencers</label>
                                <div class="col-lg-9 col-xl-9">
                                    <select class="form-control form-control-lg form-control-solid" name="created_by">
                                        <optgroup label="Users">
                                        @foreach($users as $key => $user_)
                                            <option value="{{$user_['id']}}" {{ (@$campaign && $user_['id'] == @$campaign['created_by'] ) || $user_['id']  == old('user_') ? "selected" : "" }}>{{$user_['first_name']}} ({{$user_['email']}})</option>
                                        @endforeach
                                        </optgroup>
                                        <optgroup label="Influencers">
                                        @foreach($influencers as $key => $influencer)
                                            <option value="{{$influencer['id']}}" {{ (@$campaign && $influencer['id'] == @$campaign['created_by'] ) || $influencer['id']  == old('influencer') ? "selected" : "" }}>{{$influencer['first_name']}}({{$influencer['email']}})</option>
                                        @endforeach
                                        </optgroup>

                                    </select>
                                </div>
                                @if ($errors->has('created_by'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="users" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('created_by') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="form-group row {{$errors->has('title') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Title</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="title" value="{{@$campaign->title?:old('title')}}" autocomplete="password_new" maxlength="500" placeholder="Campaign Title"/>
                                </div>
                                @if ($errors->has('title'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="title" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('title') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="form-group row {{$errors->has('description') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Description</label>
                                <div class="col-lg-9 col-xl-9">
                                    <textarea style="display: none;" id="editor" name="description">{{@$campaign['description']}}</textarea>
                                </div>
                                @if ($errors->has('description'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="description" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('description') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('location') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Location</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input id="address" class="form-control form-control-lg form-control-solid" type="text" name="location" value="{{@$campaign->location?:old('location')}}" autocomplete="password_new" maxlength="500" placeholder="Location"/>
                                    <input type="hidden" name="lat" id="lat" value="{{old('lat')?:@$campaign['lat']}}">
                                    <input type="hidden" name="lng" id="lng" value="{{old('lng')?:@$campaign['lng']}}">
                                </div>
                                @if ($errors->has('location'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="location" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('location') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('website') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Website URL</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control form-control-lg form-control-solid" type="url" name="website" value="{{@$campaign->website?:old('website')}}" autocomplete="password_new" maxlength="255" placeholder="Website URL"/>
                                </div>
                                @if ($errors->has('website'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="website" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('website') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('image') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Campaign Logo</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input type="file" name="image" data-image="logoRemove" id="input-file-now" class="dropify" accept="image/x-png,image/jpeg" @if(@$campaign['image'] == '') @else data-default-file="{{ asset('uploads/compaign/'.@$campaign['image']) }}" @endif/>
                                    <input type="hidden" value="@if(@$campaign['image'] =='')nofile @else{{@$campaign['image']}}@endif" name="logoRemove" id="logoRemove">
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


                            @if(@$campaign['id'])
                                <div class="form-group row {{$errors->has('status') ? 'has-danger' : ''}}">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Status</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control form-control-lg form-control-solid" name="status">
                                            @foreach($campaign->status() as $key => $status)
                                                <option value="{{$key}}" {{$campaign['status'] == $key ? "selected" : ""}}>{{$status}}</option>
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


