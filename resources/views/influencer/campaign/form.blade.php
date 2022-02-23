{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 

    <section class="common-form_wrapper lastsection-space_b bg-blue section-space">

        <div class="container">
            <div class="common-form_outer">
                <div class="common-form_inner">
                    <div class="common-form-content">
                        <h3 class="common-form_heading"> Create Campaign </h3>

                        <form class="common-form" action="{{ url('create/campaign') }}" method="post" id="{{  @$campaign['id'] ? 'edit-campaign' : 'create-campaign' }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{@$campaign['id']}}">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel1"> Title <span class="required">*</span></label>
                                        <input type="text" name="title" class="form-control" id="forlabel1" value="{{@$campaign->title?:old('title')}}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel2"> Description <span class="required">*</span></label>
                                        <textarea name="description" class="ckeditor" type="textarea" name="description" id="forlabel2">{{@$campaign['description']}}</textarea>
                                        @if ($errors->has('description'))
                                            <div class="fv-plugins-message-container">
                                                <div data-field="description" data-validator="notEmpty" class="fv-help-block" style="color: red;">
                                                    {{ $errors->first('description') }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel3"> Add Location <span class="required">*</span></label>
                                        <span class="add-location">
                                            <input id="address" class="form-control form-control-lg form-control-solid" type="text" name="location" value="{{@$campaign->location?:old('location')}}" autocomplete="password_new" maxlength="500" placeholder="Location"/>
                                            <input type="hidden" name="lat" id="lat" value="{{old('lat')?:@$campaign['lat']}}">
                                            <input type="hidden" name="lng" id="lng" value="{{old('lng')?:@$campaign['lng']}}">
                                        </span>

                                        @if ($errors->has('location'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="location" data-validator="notEmpty" class="fv-help-block">
                                                {{ $errors->first('location') }}
                                            </div>
                                        </div>
                                        @elseif($errors->has('lat') || $errors->has('lng'))
                                            <div class="fv-plugins-message-container">
                                                <div data-field="location" data-validator="notEmpty" class="fv-help-block">
                                                    Please select a location from suggestions.
                                                </div>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel4"> Website URL</label>
                                        <input name="website" type="url" class="form-control" id="forlabel4"
                                            placeholder="www.example.com" value="{{@$campaign->website?:old('website')}}">
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label> Campaign Logo <span class="required">*</span></label>
                                        <!-- <div class='file-input'> -->
                                            <input type="file" name="image" data-image="logoRemove" id="input-file-now" class="dropify" accept="image/x-png,image/jpeg" @if(@$campaign['image'] == '') @else data-default-file="{{ asset('uploads/compaign/'.@$campaign['image']) }}" @endif/>
                                            <input type="hidden" value="@if(@$campaign['image'] =='')nofile @else{{@$campaign['image']}}@endif" name="logoRemove" id="logoRemove">
                                            <input type="hidden" id="check_upload_orig" value="{{@$campaign['image']}}">
                                            <span class="help-block"></span>
                                        <!-- </div> -->
                                     
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="common-btns">
                                        <div class="cancel-btn">
                                            <a href="{{ url('campaigns') }}" class="flex-center"> Cancel </a>
                                        </div>
                                        <div class="create-btn flex-center ms-auto">
                                            <input id="create-campaign-submit" type="submit" value="{{ @$campaign['id']?'Update':'Create'}}" class="flex-center">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection