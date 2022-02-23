{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 


    <section class="common-form_wrapper createjob-main lastsection-space_b bg-blue section-space">

        <div class="container">
            <div class="common-form_outer">
                <div class="common-form_inner">
                    <div class="common-form-content">
                        <h3 class="common-form_heading"> {{  \Request::segment(1) == 'hire-influencer' ? "Hire Influencer" : (@$job['id'] ? 'Edit Job' : 'Create Job') }}</h3>

                        <form class="common-form" action="{{ $campaign ? url('campaign/'.$campaign['slug'].'/create/job') : url('hire-influencer') }}" method="post" id="{{  @$job['id'] ? 'edit-campaign-job' : 'create-campaign-job' }}" enctype="multipart/form-data" >
                            @csrf
                            <input type="hidden" name="id" value="{{@$job['id']}}">
                            <input type="hidden" name="influencer" value="{{@$influencer['id']}}">
                            
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel1"> Title <span class="required">*</span></label>
                                        <input type="text" class="form-control" id="forlabel1" name="title" value="{{@$job->title?:old('title')}}" autocomplete="password_new" maxlength="50" placeholder="Job Title">
                                        @if ($errors->has('title'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="title" data-validator="notEmpty" class="fv-help-block">
                                                {{ $errors->first('title') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel2"> Description <span class="required">*</span></label>
                                        <textarea class="ckeditor" type="textarea" id="forlabel2" name="description">{{@$job['description']?:old('description')}}</textarea>
                                        @if ($errors->has('description'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="description" data-validator="notEmpty" class="fv-help-block" style="color:red;">
                                                {{ $errors->first('description') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel2"> Caption <span class="required">*</span></label>
                                        <textarea class="ckeditor" type="textarea" id="caption" name="caption">{{@$job['caption']?:old('caption')}}</textarea>
                                        @if ($errors->has('caption'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="caption" data-validator="notEmpty" class="fv-help-block" style="color:red">
                                                {{ $errors->first('caption') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @if(\Request::segment(1) !== 'hire-influencer')
                                <div class="col-sm-6">
                                @else
                                <div class="col-sm-12">
                                @endif
                                    <div class="common-form_group">
                                        <label for="forlabel3"> Select Platforms <span class="required">*</span></label>
                                        <span class="select-arrow_span">
                                            <select class="form-control form-control-lg form-control-solid platforms" id="platforms" name="platforms[]" multiple>
                                                @foreach($platforms as $key => $platform)
                                                    <option value="{{$platform['id']}}" {{ (@$job && in_array($platform['id'], @$job->platformsArray())) || ( old('platforms') && in_array($platform['id'], old('platforms'))) ? "selected" : ""}}>{{$platform['name']}}</option>
                                                @endforeach
                                            </select>
                                        </span>
                                        @if ($errors->has('platforms'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="platforms" data-validator="notEmpty" class="fv-help-block">
                                                {{ $errors->first('platforms') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @if(\Request::segment(1) !== 'hire-influencer')
                                <div class="col-sm-6">
                                    <div class="common-form_group">
                                        <label for="forlabel4"> Add Categories</label>
                                        <span class="select-arrow_span">
                                            <select class="form-control form-control-lg form-control-solid" name="category">
                                                @foreach($categories as $key => $category)
                                                    <option value="{{$category['id']}}" {{ (@$job && $category['id'] == @$job['category_id'] ) || $category['id']  == old('category') ? "selected" : ""}}>{{$category['name']}}</option>
                                                @endforeach
                                            </select>
                                        </span>
                                        @if ($errors->has('category'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="category" data-validator="notEmpty" class="fv-help-block">
                                                {{ $errors->first('category') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div> 
                                @endif
                                @foreach($platforms as $key => $platform)
                                    <div class="col-sm-6 social-platform-followeres display-category-{{$platform['id']}}"  style="{{ @!$minimum_followers[strtolower($platform['name'])]?'display:none;' : 'display:block;'}}">
                                        <label for="forlabel4"> Minimum {{$platform['name']}} Followers </label>
                                        <div class="common-form_group">
                                            <input type="text" class="form-control" name="minimum_followers[{{strtolower($platform['name'])}}]" placeholder="Minmum Followers" value="{{ @$minimum_followers ? @$minimum_followers[strtolower($platform['name'])] :old('minimum_followers['.$key.']') }}">
                                            <span class="per_span"> /{{$platform['name']}} </span>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel5"> Job Cover Image <span class="required">*</span></label>
                                        <!-- <div class='file-input'> -->
                                            <input type="file" name="image" data-image="logoRemove" id="input-file-now" class="dropify" accept="image/x-png,image/jpeg" @if(@$job['image'] == '') @else data-default-file="{{ asset('uploads/job/'.@$job['image']) }}" @endif filesize="{{ 1 * 1024 * 1024 }}"/>
                                            <input type="hidden" value="@if(@$job['image'] =='')nofile @else{{@$job['image']}}@endif" name="logoRemove" id="logoRemove">
                                            <input type="hidden" id="check_upload_orig" value="{{@$job['image']}}">
                                            <span class="help-block"></span>
                                        <!-- </div> -->
                                        @if ($errors->has('image'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="image" data-validator="notEmpty" class="fv-help-block error">
                                                {{ $errors->first('image') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel5">Image/Video <span class="required">*</span></label>
                                        <!-- <div class='file-input'> -->
                                            <input type="file" name="image_video" data-image="logoRemove" id="file" class="dropify" accept="image/*,video/*"
                                             @if(@$job['image_video'] == '') @else data-default-file="{{ asset('uploads/job/'.@$job['image_video']) }}" @endif/>
                                            <input type="hidden" value="@if(@$job['image_video'] =='')nofile @else{{@$job['image_video']}}@endif" name="logoRemove" id="logoRemove">
                                            <input type="hidden" id="check_upload_orig" value="{{@$job['image_video']}}">
                                            <span class="help-block"></span>
                                        <!-- </div> -->
                                        <span id="file_error"></span>
                                        @if ($errors->has('image_video'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="image_video" data-validator="notEmpty" class="fv-help-block" style="color:red;">
                                                {{ $errors->first('image_video') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                @if(\Request::segment(1) == 'hire-influencer' || \Request::segment(1) == 'create-job')
                               
                                <!-- <input type="hidden" class="form-control" id="forlabel6" name="influencers" value="{{ @$job['influencers'] }}" autocomplete="password_new" 
                                            placeholder="5 Influencers"> -->
                                @elseif(\Request::segment(3) == 'edit')
                                 <!-- <input type="hidden" class="form-control" id="forlabel6" name="influencers" value="{{ @$job['influencers'] }}" autocomplete="password_new" 
                                            placeholder="5 Influencers"> -->
                                @else
                                @endif 
                                @if(empty(@$hirejob))
                                 @if(Request::segment(1) == 'campaign')
                                <div class="col-12 col-md-12">
                                    <div class="common-form_group">
                                        <label for="forlabel6"> Required Influencers <span class="required">*</span></label>
                                        <input type="number" class="form-control" id="forlabel6" name="influencers" value="{{@$job->influencers?:old('influencers')}}" autocomplete="password_new" 
                                            placeholder="5 Influencers">
                                        @if ($errors->has('influencers'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="influencers" data-validator="notEmpty" class="fv-help-block">
                                                {{ $errors->first('influencers') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                                @endif
                                @endif

                                @if(\Request::segment(1) == 'hire-influencer' || \Request::segment(1) == 'create-job')
                                <div class="col-12 col-md-12"> 
                                @else
                                <div class="col-12 col-md-12">
                                @endif  
                                
                                    <div class="common-form_group">
                                        <label> Time Duration <span class="required">*</span></label>
                                        <div class="time_wrapper d-flex align-items-center">
                                            <span class="select-arrow_span w-100 me-sm-4">
                                                <select id="minutes" class="form-control time_duration" name="minutes">
                                                    <option value="" selected>Select Minutes</option>
                                                    <option value="" {{ (@$job && @$job->getMinutes() == 0) || old('minutes') === 0 ? "selected" : ""}}>0</option>
                                                    @for($i = 1;$i < 60 ; $i++)
                                                        <option value="{{$i}}" {{ (@$job && @$job->getMinutes() == $i) || old('minutes') == $i ? "selected" : ""}}>{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </span>
                                            <span class="select-arrow_span w-100">
                                                <select id="seconds" class="form-control" name="seconds">
                                                    <option value="" selected>Select Seconds</option>
                                                    <option value="" {{ (@$job && @$job->getSeconds() == 0) || old('seconds') === 0  ? "selected" : ""}}>0</option>
                                                    @for($i = 1;$i < 60 ; $i++)
                                                        <option value="{{$i}}" {{ (@$job && @$job->getSeconds() == $i) || old('seconds') == $i  ? "selected" : ""}}>{{$i}}</option>
                                                    @endfor
                                                </select>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="address"> Location </label>
                                        <input type="text" class="form-control" id="address" name="location" placeholder="Location" value="{{ @$job['location']?:old('location') }}">
                                        <input type="hidden" name="lat" id="lat" value="{{old('lat')}}">
                                        <input type="hidden" name="lng" id="lng" value="{{old('lng')}}">
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel1"> Radius </label>
                                        <input type="number" class="form-control" id="forlabel1" name="radius" value="{{@$job->radius?:old('radius')}}" autocomplete="password_new" maxlength="500" placeholder="Job radius">
                                        @if ($errors->has('radius'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="radius" data-validator="notEmpty" class="fv-help-block">
                                                {{ $errors->first('radius') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <?php

                                  
                                ?>
                                @if(\Request::segment(1) !== 'hire-influencer')
                                <div class="col-12 col-md-12">
                                    <div class="common-form_group">
                                        <label> Age </label>

                                        <div class="row">
                                            <div class="col-6">
                                                <input type="number" class="form-control" id="min_age" name="min_age" placeholder="Min Age" min="0" maxlength="3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{@$job['min_age']}}">
                                                @if ($errors->has('min_age'))
                                                    <div class="fv-plugins-message-container">
                                                        <div data-field="min_age" data-validator="notEmpty" class="fv-help-block" style="color:red;">
                                                            {{ $errors->first('min_age') }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-6">
                                            <input type="number" class="form-control" id="max_age" name="max_age" placeholder="Max Age"  min="0" maxlength="3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{@$job['max_age']}}">
                                             @if ($errors->has('max_age'))
                                                <div class="fv-plugins-message-container">
                                                    <div data-field="max_age" data-validator="notEmpty" class="fv-help-block" style="color:red;">
                                                        {{ $errors->first('max_age') }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="common-form_group">
                                        <label> Race </label>
                                        <div class="d-flex align-items-center">
                                            <span class="select-arrow_span w-100">
                                                <select class="form-control form-control-lg form-control-solid platforms" id="race_id" name="race_id[]" multiple>

                                                    @foreach($race as $key => $row)
                                                    <option value="{{$row['id']}}" {{ @$job && in_array($row['id'], @$race_id?:[]) ? "selected" : ""}}>{{$row['title']}}</option>
                                                    @endforeach
                                                    
                                                </select>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @else
                                
                                @endif

                               @if($campaign)
                                <div class="col-sm-6">
                                    <div class="common-form_group">
                                        <label for="promo_days"> Promotion Days </label>
                                        <input type="text" class="form-control" id="promo_days"  name="promo_days" value="{{@$job->promo_days?:old('promo_days')}}" autocomplete="password_new" placeholder="30 Days">
                                        @if ($errors->has('promo_days'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="promo_days" data-validator="notEmpty" class="fv-help-block">
                                                {{ $errors->first('promo_days') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                @else
                                <div class="col-sm-12">
                                @endif
                                    <div class="common-form_group">
                                        <label for="forlabel8"> Price ($)  <span class="required">*</span></label>
                                        <div class="price-input_out"> 
                                        <input type="number" min="0" class="form-control" id="forlabel8" name="price" value="{{@$job->price?:old('price')}}" autocomplete="password_new" placeholder="Amount">
                                       
                                            
                                            <span class="per_span">
                                                <?php if(\Request::segment(1) == 'hire-influencer'){

                                                } else if (\Request::segment(3) == 'edit'){

                                                } else {
                                                    echo "Per Influencer/Post";
                                                }
                                                ?>
                                                </span>
                                        @if ($errors->has('price'))
                                        <div class="fv-plugins-message-container">
                                            <div data-field="price" data-validator="notEmpty" class="fv-help-block">
                                                {{ $errors->first('price') }}
                                            </div>
                                        </div>
                                        @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="common-btns">
                                        <div class="cancel-btn">
                                            <a href="{{ $campaign ? url('campaign/'.$campaign['slug']) : url('/') }}" class="flex-center"> Cancel </a>
                                        </div>
                                        <div class="create-btn flex-center">
                                            <input id="create-campaign-job-submit" type="submit" value="{{ @$job['id']?'Update':'Create'}}" class="flex-center">
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

