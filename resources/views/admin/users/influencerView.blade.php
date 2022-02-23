{{-- Extends layout --}}
@extends('admin.layout.default')

{{-- Content --}}
@section('content')
 
    {{-- Dashboard 1 --}}

    <!--begin::Profile Overview-->
    <div class="d-flex flex-row">
        <!--begin::Aside-->

        @if( $page !== 'add')

        <div class="flex-row-auto offcanvas-mobile w-300px w-xl-350px" id="kt_profile_aside">
            <!--begin::Profile Card-->
            <div class="card card-custom card-stretch">
                <!--begin::Body-->
                <div class="card-body pt-4">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end">
                        <?php /*
                        <div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ki ki-bold-more-hor"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <!--begin::Navigation-->
                                <ul class="navi navi-hover py-5">
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="flaticon2-drop"></i>
                                            </span>
                                            <span class="navi-text">New Group</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="flaticon2-list-3"></i>
                                            </span>
                                            <span class="navi-text">Contacts</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="flaticon2-rocket-1"></i>
                                            </span>
                                            <span class="navi-text">Groups</span>
                                            <span class="navi-link-badge">
                                                <span class="label label-light-primary label-inline font-weight-bold">new</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="flaticon2-bell-2"></i>
                                            </span>
                                            <span class="navi-text">Calls</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="flaticon2-gear"></i>
                                            </span>
                                            <span class="navi-text">Settings</span>
                                        </a>
                                    </li>
                                    <li class="navi-separator my-3"></li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="flaticon2-magnifier-tool"></i>
                                            </span>
                                            <span class="navi-text">Help</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="flaticon2-bell-2"></i>
                                            </span>
                                            <span class="navi-text">Privacy</span>
                                            <span class="navi-link-badge">
                                                <span class="label label-light-danger label-rounded font-weight-bold">5</span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                                <!--end::Navigation-->
                            </div>
                        </div> */ ?>
                    </div>
                    <!--end::Toolbar-->
                    <!--begin::User-->
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                           <!--  <div class="symbol-label" style="background-image:url('{{ @$user_ ? @$user_->getProfileImage() : '' }}')"></div> -->
                            <div class="symbol-label" style="background-image:url('{{ @$user_ ? @$user_->getProfileImage() : '' }}')">
                                
                            </div>
                            <i class="symbol-badge bg-success"></i>
                        </div>
                        <div>
                            <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">{{$user_['first_name']. ' '. $user_['last_name']}}</a>
                            <div class="text-muted">{{@$user_['type'] == 1 ? 'User' : 'Influencer' }}</div>
                            <!-- <div class="mt-2">
                                <a href="#" class="btn btn-sm btn-primary font-weight-bold mr-2 py-2 px-3 px-xxl-5 my-1">Chat</a>
                                <a href="#" class="btn btn-sm btn-success font-weight-bold py-2 px-3 px-xxl-5 my-1">Follow</a>
                            </div> -->
                        </div>
                    </div>
                    <!--end::User-->
                    <!--begin::Contact-->
                    <div class="py-9">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">Email:</span>
                            <a href="#" class="text-muted text-hover-primary">{{@$user_['email']}}</a>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="font-weight-bold mr-2">Phone:</span>
                            <span class="text-muted">{{@$user_['phone']}}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between social-links">
                            <span class="font-weight-bold mr-2">Social:</span>
                            <span class="text-muted">    
                                <i class="fa fa-instagram">{{@$user_instagram_platform->followers}}</i>
                                <i class="fa fa-facebook"><spam>{{@$user_facebook_platform->followers}}</spam></i>
                                <i class="fa fa-youtube">{{@$user_youtube_platform->followers}}</i>
                                <i class="fab fa-tiktok">{{@$user_tiktok_platform->followers}}</i>
                                <i class="fa fa-twitter">{{@$user_twitter_platform->followers}}</i>
                            </span>
                        </div>
                    </div>
                    <!--end::Contact-->
                    <!--begin::Nav-->
                    <div class="navi navi-bold navi-hover navi-active navi-link-rounded">
                        <div class="navi-item mb-2 ">
                            <a href="{{url('admin/user')}}/{{@$user_['id']}}" class="navi-link py-4 active">
                                <span class="navi-icon mr-2">
                                    <span class="svg-icon">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                </span>
                                <span class="navi-text font-size-lg">Personal Information</span>
                            </a>
                        </div>
                    </div>
                    <!--end::Nav-->
                </div>
                <!--end::Body-->
            </div>
            <!--end::Profile Card-->
        </div>

        @endif
        <!-- End of view if -->

        <!--end::Aside-->
        <!--begin::Content-->
        <div class="flex-row-fluid ml-lg-8">
            <form action="{{url('admin/influenceredit')}}" method="post" id="user_update_form" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="user_id" value="{{@$user_['id']}}">
                <!--begin::Advance Table: Widget 7-->
                <div class="card card-custom card-stretch">
                    <!--begin::Header-->
                    <div class="card-header py-3">
                        <div class="card-title align-items-start flex-column">
                            <h3 class="card-label font-weight-bolder text-dark">Personal Information</h3>
                            @if($page !== 'view')   
                                <span class="text-muted font-weight-bold font-size-sm mt-1">Update informaiton</span>
                            @endif
                        </div>
                        <div class="card-toolbar">
                            @if($page !== 'view') 
                            <input type="button" id="update_user_submit" name="" class="btn btn-success mr-2 update_user_submit" value="Save" />
                            <a href="{{url('admin/users')}}" class="btn btn-secondary">Cancel</a>
                            @endif
                        </div>
                    </div>
                    <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body">
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mb-6">Influencer Info</h5>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-xl-3 col-lg-3 col-form-label">Profile Image</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-image: url('{{ @$user_ ? $profile_image = @$user_->getProfileImage() : ''}}')"> 
                                        <div class="image-input-wrapper" style="background-image: url('{{@$user_ ? @$user_->getProfileImage() : '' }}')"></div>
                                        @if($page !== 'view') 
                                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change picture">
                                            <i class="fa fa-pencil icon-sm text-muted"></i>
                                            <input type="file" id="profile-image-upload" name="profile_avatar" accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" id="profile_avatar_remove" name="profile_avatar_remove" />
                                        </label>
                                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel picture">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                        <span id="remove-avatar" class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove picture" style="{{isset($profile_image) && strpos($profile_image , 'default') !== false ? 'display:none;':'' }} ssdsdsds">
                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                        </span>
                                        @endif
                                    </div>
                                   @if($page !== 'view')  
                                    <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{$errors->has('first_name') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">First Name</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-user"></i>
                                            </span>
                                        </div>
                                        <input class="form-control form-control-lg form-control-solid" maxlength="50" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' type="text" name="first_name" value="{{@$user_['first_name']?:old('first_name')}}" {{$page == 'view' ? 'readonly' : ''}} />
                                    </div>
                                    @if ($errors->has('first_name'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="first_name" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('first_name') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{$errors->has('last_name') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Last Name</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-user"></i>
                                            </span>
                                        </div>
                                        <input class="form-control form-control-lg form-control-solid" maxlength="50" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' type="text" name="last_name" value="{{@$user_['last_name']?:old('last_name')}}" {{$page == 'view' ? 'readonly' : ''}} />
                                    </div>
                                    @if ($errors->has('last_name'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="last_name" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('last_name') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group row {{$errors->has('type') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">User Type</label>
                                <div class="col-lg-9 col-xl-6">
                                    <select id="type_select" class="form-control" name="type" {{$page == 'view' ? 'disabled' : ''}}>
                                        <option value="">Please select Influencer</option>
                                        <option value="2" {{@$user_['type'] == 2 || old('type') == 2 ? 'selected' : ''}} >Influencer</option>
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

                            
                            <div class="form-group row category {{$errors->has('category') ? 'has-danger' : ''}}" style="{{@$user_['type'] == 2 ? '' : 'display:none'}} ">
                                <label class="col-xl-3 col-lg-3 col-form-label">Category</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <select id="category" class="form-control" name="category" {{$page == 'view' ? 'disabled' : ''}}>
                                            <option value="">Please select influencer category</option>
                                            @if(isset($categories) && $categories)
                                            @foreach($categories as $category)
                                            <option value="{{$category['id']}}" {{old('category') == $category['id'] ||$user_['category'] == $category['id'] ? 'selected': ''  }}>{{$category['name']}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('category'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="category" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('category') }}
                                        </div>
                                    </div>
                                    @endif
                                    <span id="error_ids"></span>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-xl-3"></label>
                                <div class="col-lg-9 col-xl-6">
                                    <h5 class="font-weight-bold mt-10 mb-6">Contact Info</h5>
                                </div>
                            </div>
                            <div class="form-group row {{$errors->has('country_code') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Country Code</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-flag-checkered"></i>
                                            </span>
                                        </div>
                                        <select class="form-control" name="country_code" {{$page == 'view' ? 'disabled' : ''}}>
                                            <option value="">Please select country code</option>
                                            @if(@$countries)
                                            @foreach(@$countries as $key=>$country)
                                                <option value="{{$country->id}}" {{@$user_['country_code'] == $country->id || old('country_code') == $country->id  ? 'selected' : ''}} >+{{$country->code}} [{{$country['name']}}]</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    @if ($errors->has('country_code'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="country_code" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('country_code') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{$errors->has('phone') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Contact Phone</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-phone"></i>
                                            </span>
                                        </div>
                                        <input type="number" min="0" id="phone" class="form-control form-control-lg form-control-solid" name="phone" value="{{@$user_['phone']?: old('phone')}}" placeholder="Phone" {{$page == 'view' ? 'readonly' : ''}} maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"/>
                                      
                                    </div>
                                    @if ($errors->has('phone'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="phone" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('phone') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{$errors->has('dob') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Date of Birth</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-dob"></i>
                                            </span>
                                        </div>
                                        <input type="text" id="dob" class="form-control form-control-lg form-control-solid datepicker" name="dob" value="{{@$user_['date_of_bith']?: old('dob')}}" placeholder="Date of Birth" {{$page == 'view' ? 'readonly' : ''}} readonly/>
                                    </div>
                                    @if ($errors->has('dob'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="dob" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('dob') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{$errors->has('email') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Email Address</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-at"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="email" value="{{@$user_['email']?: old('email')}}" placeholder="Email" {{$page == 'view' ? 'readonly' : ''}}/>
                                    </div>
                                    @if ($errors->has('email'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="email" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('email') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if($page == 'add')

                            <div class="form-group row {{$errors->has('password') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Password</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-key"></i>
                                            </span>
                                        </div>
                                        <input id="password" type="password" class="form-control form-control-lg form-control-solid" name="password" value="{{@$user_['password']}}" placeholder="Password" {{$page == 'view' ? 'readonly' : ''}}/>
                                        <div class="view_password" for="password"><i class="fa fa-eye" aria-hidden="true"></i></div>
                                    </div>
                                    @if ($errors->has('password'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="password" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('password') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @endif

                            <div class="form-group row {{$errors->has('city') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Address</label>
                                <div class="col-lg-9 col-xl-6 multi-form-group">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-building-o"></i>
                                            </span>
                                        </div>
                                        <input id="address" type="text" class="form-control form-control-lg form-control-solid" name="address" value="{{old('address')?: @$user_['address'] }}" placeholder="Address" {{$page == 'view' ? 'readonly' : ''}}/>
                                        <input type="hidden" name="city" id="city" value="{{old('city')?:@$user_['city']}}">
                                        <input type="hidden" name="state" id="state" value="{{old('state')?:@$user_['state']}}">
                                        <input type="hidden" name="country" id="country" value="{{old('country')?:@$user_['country']}}">
                                        <input type="hidden" name="lat" id="lat" value="{{old('lat')?:@$user_['lat']}}">
                                        <input type="hidden" name="lng" id="lng" value="{{old('lng')?:@$user_['lng']}}">
                                    </div>
                                    @if ($errors->has('city'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="city" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('city') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{$errors->has('school') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">School (Optional)</label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-bank"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg form-control-solid" name="school" value="{{@$user_['school']?:old('school')}}" placeholder="School, Highschool, College (Optional)" {{$page == 'view' ? 'readonly' : ''}}/>
                                    </div>
                                    @if ($errors->has('school'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="school" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('school') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row {{$errors->has('status') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">Status</label>
                                <div class="col-lg-9 col-xl-6">
                                    <select class="form-control" name="status" {{$page == 'view' ? 'disabled' : ''}}>
                                        <option value="">Select Status</option>
                                        <option value="1" {{@$user_['status'] == 1 || old('status') == 1 ? 'selected' : ''}}>Pending</option>
                                        <option value="2" {{@$user_['status'] == 2 || old('status') == 2 ? 'selected' : ''}} >Active</option>
                                        <option value="3" {{@$user_['status'] == 3 || old('status') == 3 ? 'selected' : ''}} >Deactivate</option>
                                        <option value="4" {{@$user_['status'] == 4 || old('status') == 4 ? 'selected' : ''}} >Ban</option>
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
                            <div class="form-group row {{$errors->has('race_id') ? 'has-danger' : ''}}">
                                <label class="col-xl-3 col-lg-3 col-form-label">User Race </label>
                                <div class="col-lg-9 col-xl-6">
                                    <select class="form-control form-control-lg form-control-solid" name="race_id">
                                        <option value="">Select race</option>
                                        @foreach($race as $key => $row)
                                            <option value="{{$row['id']}}" {{ (@$row['id'] == @$user_['race_id'] ) || $row['id']  == old('race_id') ? "selected" : "" }}>{{$row['title']}}</option>
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
                            <div class="form-group row {{$errors->has('bio') ? 'has-danger' : ''}}" style="{{@$user_['id']? 'block' : 'display:none'}}">
                                <label class="col-xl-3 col-lg-3 col-form-label" placeholder="Maximum 250 words">Bio <small>(Max 250 words)  </small></label>
                                <div class="col-lg-9 col-xl-6">
                                    <div class="input-group input-group-lg input-group-solid">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="la la-user"></i>
                                            </span>
                                        </div>
                                        
                                        <textarea class="form-control form-control-lg form-control-solid" rows="6" maxlength="250" name="description">{{@$user_['description']}} </textarea>
                                    </div>
                                    @if ($errors->has('bio'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="bio" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('bio') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if($page !== 'view') 
                            <div class="form-group row">
                                <div class="col-xl-3 col-lg-3"></div>
                                <div class="col-lg-9 col-xl-6">
                                    <input type="button" name="" class="btn btn-success mr-2 update_user_submit" value="Save" />
                                    <a href="{{url('admin/users')}}" class="btn btn-secondary">Cancel</a>
                                </div>
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
