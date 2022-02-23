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
            <form action="{{ @$campaign_id ? url('admin/campaign/'.$campaign_id.'/job/save') : url('admin/job/save')}}" method="post" id="{{@$job['id'] ? 'job_form_edit' : 'job_form'}}" enctype="multipart/form-data" autocomplete="off">
               @csrf
               <input type="hidden" name="id" id="job_id" value="{{@$job['id']}}">
                <!--begin::Advance Table: Widget 7-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">{{@$job['id'] ? "Edit" : "Add" }} Job</h3>
                                <!-- <span class="text-muted font-weight-bold font-size-sm mt-1">Update informaiton</span> -->
                        </div>
                        <div class="card-toolbar">
                            <input type="submit" id="job_form_submit" name="" class="btn btn-success mr-2" value="Save" />
                            <a href="{{ @$campaign_id ? url('admin/campaign/'.$campaign_id.'/jobs') : url('admin/jobs')}}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                    <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mb-6">Job Details</h5>
                                </div>
                            </div>
                            @if(\Request::segment(3) == 'add' || \Request::segment(3) == 'edit' ) 
                            <div class="form-group row {{$errors->has('category') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Users/Influencers</label>
                                <div class="col-lg-9 col-xl-9">
                                    <select class="form-control form-control-lg form-control-solid" name="created_by">
                                        <optgroup label="Users">
                                        @foreach($users as $key => $user_)
                                            <option value="{{$user_['id']}}" {{ (@$job && $user_['id'] == @$job['created_by'] ) || $user_['id']  == old('user_') ? "selected" : "" }}>{{$user_['first_name']}} ({{$user_['email']}})</option>
                                        @endforeach
                                        </optgroup>
                                        <optgroup label="Influencers">
                                        @foreach($influencers as $key => $influencer)
                                            <option value="{{$influencer['id']}}" {{ (@$job && $influencer['id'] == @$job['created_by'] ) || $influencer['id']  == old('influencer') ? "selected" : "" }}>{{$influencer['first_name']}}({{$influencer['email']}})</option>
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
                            @endif
                            <div class="form-group row {{$errors->has('title') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Title</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="title" value="{{@$job->title?:old('title')}}" autocomplete="password_new" maxlength="200" placeholder="Job Title"/>
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
                                    <textarea style="display: none;" id="editor" name="description">{{@$job['description']?:old('description')}}</textarea>
                                </div>
                                @if ($errors->has('description'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="description" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('description') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('caption') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Caption</label>
                                <div class="col-lg-9 col-xl-9">
                                    <textarea class="ckeditor" type="textarea" id="forlabel2" name="caption">{{@$job['caption']?:old('caption')}}</textarea>
                                @if ($errors->has('caption'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="caption" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('caption') }}
                                    </div>
                                </div>
                                @endif
                                </div>
                            </div>
                            <div class="{{$errors->has('platforms') ? 'has-danger' : ''}}">
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Add Platforms</label>
                                <div class="col-lg-9 col-xl-9">
                                    <select class="form-control form-control-lg form-control-solid" id="platforms" name="platforms[]" multiple>
                                        @foreach($platforms as $key => $platform)
                                            <option value="{{$platform['id']}}" {{ (@$job && in_array($platform['id'], @$job->platformsArray())) || ( old('platforms') && in_array($platform['id'], old('platforms'))) ? "selected" : ""}}>{{$platform['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>  
                                @if ($errors->has('platforms'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="platforms" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('platforms') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            </div>
                            @foreach($platforms as $key => $platform)
                                    <div class="social-platform-followeres display-category-{{$platform['id']}}"  style="{{ @!$minimum_followers[strtolower($platform['name'])]?'display:none;' : 'display:block;'}}">
                                    <div class="form-group row">
                                        <div class="col-lg-3"><label for="forlabel4"> Minimum {{$platform['name']}} Followers </label></div>
                                        <div class="col-lg-9">
                                        <div class="common-form_group">
                                            <input type="text" class="form-control" name="minimum_followers[{{strtolower($platform['name'])}}]" placeholder="Minmum Followers {{$platform['name']}}" value="{{ @$minimum_followers ? @$minimum_followers[strtolower($platform['name'])] :old('minimum_followers') }}">
                                            <!--<span class="per_span"> /{{$platform['name']}} </span>-->
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                @endforeach

                            <div class="form-group row {{$errors->has('category') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Add Category</label>
                                <div class="col-lg-9 col-xl-9">
                                    <select class="form-control form-control-lg form-control-solid" name="category">
                                        @foreach($categories as $key => $category)
                                            <option value="{{$category['id']}}" {{ (@$job && $category['id'] == @$job['category_id'] ) || $category['id']  == old('category') ? "selected" : "" }}>{{$category['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('category'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="category" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('category') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="form-group row {{$errors->has('image') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Job Cover Image</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input type="file" name="image" data-image="logoRemove" id="input-file-now" class="dropify" accept="image/x-png,image/jpeg" @if(@$job['image'] == '') @else data-default-file="{{ asset('uploads/job/'.@$job['image']) }}" @endif/>
                                    <input type="hidden" value="@if(@$job['image'] =='')nofile @else{{@$job['image']}}@endif" name="logoRemove" id="logoRemove">
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

                            <div class="form-group row {{$errors->has('image_video') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Image/Video</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input type="file" name="image_video" data-image="logoRemove" id="file" class="dropify" accept="image/*,video/*" @if(@$job['image_video'] == '') @else data-default-file="{{ asset('uploads/job/'.@$job['image_video']) }}" @endif/>
                                    <input type="hidden" value="@if(@$job['image_video'] =='')nofile @else{{@$job['image_video']}}@endif" name="logoRemove" id="logoRemove">
                                    <span class="help-block"></span>
                                    <span id="file_error"></span>
                                </div>
                                @if ($errors->has('image_video'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="image_video" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('image_video') }}
                                    </div>
                                </div>
                                @endif

                            </div>
                        @if(empty(@$hirejob))
                            @if(Request::segment(2) == 'campaign')
                            <div class="form-group row {{$errors->has('influencers') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Required Influencers</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control form-control-lg form-control-solid" type="number" name="influencers" id="influencers" value="{{@$job->influencers?:old('influencers')}}" autocomplete="password_new" placeholder="Number of Required Influencers"/>
                                </div>
                                @if ($errors->has('influencers'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="influencers" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('influencers') }}
                                    </div>
                                </div>
                                @endif
                            </div> 
                            @endif
                        @endif
                            
                            <div class="form-group row {{$errors->has('minutes') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Time Duration</label>
                                <div class="col-lg-4 col-xl-4">
                                    <select id="minutes" class="form-control form-control-lg form-control-solid" name="minutes">
                                        <option value="" disabled selected>Select Minutes</option>
                                        <option value="0" >0</option>
                                        @for($i = 1;$i < 60 ; $i++)
                                            <option value="{{$i}}" {{ (@$job && @$job->getMinutes() == $i) || old('minutes') == $i ? "selected" : "" }}>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-lg-1 col-xl-1"></div>
                                <div class="col-lg-4 col-xl-4">
                                    <select id="seconds" class="form-control form-control-lg form-control-solid" name="seconds">
                                        <option value="" disabled selected>Select Seconds</option>
                                        <option value="0" >0</option>
                                        @for($i = 1;$i < 60 ; $i++)
                                            <option value="{{$i}}" {{ (@$job && @$job->getSeconds() == $i) || old('seconds') == $i  ? "selected" : "" }}>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                @if ($errors->has('influencers'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="influencers" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('influencers') }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if(Request::segment(2) == 'job')
                            @else
                            <div class="form-group row {{$errors->has('promo_days') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Promotion Days</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control form-control-lg form-control-solid" type="number" name="promo_days" value="{{@$job->promo_days?:old('promo_days')}}" autocomplete="password_new" placeholder="Promotion Days"/>
                                </div>
                                @if ($errors->has('promo_days'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="promo_days" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('promo_days') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            
                            <div class="form-group row {{$errors->has('price') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Price($)</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control form-control-lg form-control-solid" type="number" name="price" value="{{@$job->price?:old('price')}}" autocomplete="password_new" placeholder="Price"/>
                                </div>
                                @if ($errors->has('price'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="price" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('price') }}
                                    </div>
                                </div>
                                @endif

                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Location</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control form-control-lg form-control-solid" type="text" name="location" placeholder="Location" id="address" value="{{@$job->location?:old('location')}}"/>
                                    <input type="hidden" name="lat" id="lat" value="{{old('lat')}}">
                                    <input type="hidden" name="lng" id="lng" value="{{old('lng')}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Radius</label>
                                <div class="col-lg-9 col-xl-9">
                                    <input class="form-control form-control-lg form-control-solid" type="number" name="radius" placeholder="Radius" id="address" value="{{@$job->radius?:old('radius')}}"/>
                                  
                                </div>
                            </div>
                           @if(\Request::segment(2) == 'job') 
                            <div class="form-group row {{$errors->has('minutes') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Age</label>
                                <div class="col-lg-4 col-xl-4">
                                   <input type="number" class="form-control" id="min_age" min="0" name="min_age" maxlength="3" placeholder="Min Age" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"value="{{@$job->min_age?:old('min_age')}}"> 
                                @if ($errors->has('min_age'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="min_age" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('min_age') }}
                                    </div>
                                </div>
                                @endif
                                </div>
                                <div class="col-lg-1 col-xl-1"></div>
                                <div class="col-lg-4 col-xl-4">
                                   <input type="number" min="0" class="form-control" id="max_age" name="max_age" maxlength="3" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Max Age" value="{{@$job->max_age?:old('max_age')}}"> 
                                @if ($errors->has('max_age'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="max_age" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('max_age') }}
                                    </div>
                                </div>
                                @endif
                                </div>
                            </div>
                            <div class="form-group row {{$errors->has('category') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Add Race</label>
                                <div class="col-lg-9 col-xl-9">
                                    <select class="form-control form-control-lg form-control-solid platforms" id="race_id" name="race_id[]" multiple>
                                        <option value="">Select race</option>
                                        @foreach($race as $key => $row)
                                                    <option value="{{$row['id']}}" {{ @$job && in_array($row['id'], @$race_id?:[]) ? "selected" : ""}}>{{$row['title']}}</option>
                                       @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('race'))
                                <div class="fv-plugins-message-container">
                                    <div data-field="race" data-validator="notEmpty" class="fv-help-block">
                                        {{ $errors->first('race') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif 

                      
                        


                            @if(@$job['id'])
                                <div class="form-group row {{$errors->has('status') ? 'has-danger' : ''}}">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Status</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <select class="form-control form-control-lg form-control-solid" name="status">
                                            @foreach($job->status() as $key => $status)
                                                <option value="{{$key}}" {{$job['status'] == $key ? "selected" : ""}}>{{$status}}</option>
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

                        </div>
                        <!--end::Body-->
                </div>
                <!--end::Advance Table Widget 7-->
            </form>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Profile Overview-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
<script type="text/javascript">
  $(function() {
    $('#platforms').change(function(e) {
        var selected = $(this).val();
        $('.social-platform-followeres').hide();
        selected.forEach(function(value, index){
            $('.display-category-'+value).show();
            console.log($('.display-category-'+value).length, value, index);
        });
    }); 
});
</script>
@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/pages/widgets.js') }}" type="text/javascript"></script>
@endsection


