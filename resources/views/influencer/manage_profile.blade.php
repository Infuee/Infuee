{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           

    <div class="bg-blue transaction_box lastsection-space_b">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 col-md-10 col-sm-12 mx-auto section-space">
                            @include('user-flash-message')
                            <div class="signup_box manage_profile edit-profile_i common-form">
                                <h2 class="welcome_text">Edit Account Info</h2>
                                <form id="manage_profile_form" action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="{{$user['type']}}">
                                    <div class="signup_form login_form">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <div class="form-group">
                                                    <div class="upload_profile">
                                                        @if(is_file(public_path("/media/users").'/'.@$user['image']))
                                                            <img src="{{asset('media/users/'.$user['image'])}}" id="blah">
                                                        @else
                                                            <img src="media/users/blank.png" id="blah">
                                                        @endif
                                                        <div class="upload-btn-wrapper">
                                                            <button class="upload_profile_btn" onclick="pickImage(this)"><i class="fa fa-camera"></i></button>
                                                            <input type="file" name="myfile" id="profileImage" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group common-form_group">
                                                    <label>First Name</label>
                                                    <div class="Pos-R">
                                                        <input type="text" placeholder="Name" maxlength="50" onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' name="first_name" class="form-control" value="{{$user['first_name']}}">
                                                        <!-- <div class="edit_icon">
                                                            <img src="media/images/edit.png">
                                                        </div>-->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group common-form_group">
                                                    <label>Last Name</label>
                                                    <div class="Pos-R">
                                                        <input type="text" placeholder="Name" maxlength="50"  onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' name="last_name" class="form-control"  value="{{$user['last_name']}}">
                                                        <!-- <div class="edit_icon">
                                                            <img src="media/images/edit.png">
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group common-form_group">
                                                    <label>Date Of Birth</label>
                                                    <div class="Pos-R">
                                                        <input type="text" placeholder="17/3/1992" name="date_of_bith" class="form-control" value="{{@$user['date_of_bith'] ? date('d/m/Y', strtotime(@$user['date_of_bith'])) : ''}}" readonly id="datepicker">
                                                        <!-- <div class="edit_icon">
                                                            <img src="media/images/edit.png">
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4">
                                                <div class="form-group common-form_group">
                                                    <label>Country Code</label>
                                                    <div class="Pos-R">
                                                        <span class="select-arrow_span"> 
                                                        <select class="form-control js-example-basic-single" name="country_code">
                                                            <option value="">Select Your Country Code</option>
                                                            @foreach(@$countries as $country)
                                                                <option value="{{$country->id}}" {{old('country_code') == $country->id || $user['country_code'] == $country->id ? 'selected':''}}>+{{$country->code}} [{{ $country['name'] }}]</option>
                                                            @endforeach
                                                        </select>
                                                        </span>
                                                        <!-- <div class="arrow_icon">
                                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <div class="form-group common-form_group">
                                                    <label>Phone Number</label>
                                                    <input type="number" placeholder="Phone Number" name="phone" class="form-control" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{old('phone')?:$user['phone']}}">
                                                </div>
                                            </div>
                                            @if(!$user->isUser())
                                                <div class="col-md-12">
                                                    <div class="form-group common-form_group">
                                                        <label>Category</label>
                                                        <div class="Pos-R">
                                                            <select class="form-control js-example-basic-single" name="category">
                                                                <option value="">Select Your Catrgory</option>
                                                                @foreach(@$categories as $category)
                                                                    <option value="{{$category['id']}}" {{old('category') == $category['id'] || $user['category'] == $category['id'] ? 'selected':''}}>{{$category['name']}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="arrow_icon">
                                                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-12">
                                                <div class="form-group multi-form-group  common-form_group">
                                                    <label>Address</label>
                                                    <input id="address" type="text" placeholder="Address" name="address" class="form-control" value="{{old('address')?:@$user['address']}}">
                                                    <input type="hidden" name="city" id="city" value="{{old('city')?:$user['city']}}">
                                                    <input type="hidden" name="state" id="state" value="{{old('state')?:$user['state']}}">
                                                    <input type="hidden" name="country" id="country" value="{{old('country')?:$user['country']}}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group  common-form_group">
                                                    <label>School, Highschool, College (Optional)</label>
                                                    <input type="text" name="school" placeholder="School, Highschool, College (Optional)" class="form-control" value="{{old('school')?:$user['school']}}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group  common-form_group">
                                                    <label>User Bio <small>(Max 250 words)  </small>(Optional)</label>
                                                    <textarea class="form-control-lg form-control-solid" maxlength="250" name="description">{{@$user['description']}} </textarea>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group  common-form_group">
                                                    <label>Facebook Profile Link (Optional)</label>
                                                    <input type="text" name="facebook" placeholder="Facebook (Optional)" class="form-control" value="{{old('facebook')?:$user->facebook()}}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group  common-form_group">
                                                    <label>Twitter Profile Link (Optional)</label>
                                                    <input type="text" name="twitter" placeholder="Twitter (Optional)" class="form-control" value="{{old('twitter')?:$user->twitter()}}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group  common-form_group">
                                                    <label>Pinterest Profile Link (Optional)</label>
                                                    <input type="text" name="pinterest" placeholder="Pinterest (Optional)" class="form-control" value="{{old('pinterest')?:$user->pinterest()}}">
                                                </div>
                                            </div>
  
                                            <div class="col-md-12">
                                                <div class="form-group text-center  common-form_group">
                                                    <button class="btn blue-btn" id="manage_profile_submit">Save</button>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="factor_title text-center">
                                                    <h3>Two-Factor Authentication</h3>
                                                    <p>Want an extra level of account security? Enable Two-factor authentication.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        @if(@$user['is_two_fa'])
                                        <a href="javascript:void(0);" class="get_started enabled" id="disable2fa">Disable Two-Factor Authentication</a>
                                        @else
                                        <a href="javascript:void(0);" class="dark-btn" data-bs-toggle="modal" data-bs-target="#enable_modal">Enable Two-Factor Authentication</a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
   
<!-- Modal -->
<div class="filter_modal authentication_modal">
    <div class="modal fade" id="enable_modal" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title text-center" id="myModalLabel">Enable Two-Factor Authentication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{url('enablefa')}}" method="post" id="enable-two-factor-form">
                        @csrf

                    <div class="filter_form">
                        <div class="row">
                            <div class="col-sm-4 pe-sm-0">
                                <div class="form-group">
                                    <label>Country Code</label>
                                    <div class="Pos-R">
                                       <select class="form-control js-example-basic-single" name="country_code">
                                                            <option value="">Select Your Country Code</option>
                                                            @foreach(@$countries as $country)
                                                                <option value="{{$country->id}}" {{old('country_code') == $country->id || $user['country_code'] == $country->id ? 'selected':''}}>+{{$country->code}} [{{ $country['name'] }}]</option>
                                                            @endforeach
                                                        </select>
                                        <!--<div class="arrow_icon">
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8 ps-sm-0">
                                <div class="form-group">
                                    <label>Mobile Number</label>
                                    <div class="form-group">
                                        <input type="number" placeholder="Phone Number" id="phone" name="phone" class="form-control" maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{old('phone')?:$user['phone']}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label>Current Password</label>
                                <div class="form-group eyes">
                                    <input id="password" name="password" type="password" placeholder="Password" class="form-control">
                                    <div class="view_password"><i class="fa fa-eye" aria-hidden="true"></i></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                    <div class="error_msg" id="password-error-message" style="display: none;">
                                        <p></p>
                                    </div>
                                </div>
                            <div class="col-md-12 text-center">
                                <div class="view_result authentication_btns">
                                    <a id="generate_OTP" href="javascript:void(0);"  class="get_started btn blue-btn">Request OTP</a>
                                    <a href="javascript:void(0);" class="get_started cancel_btn dark-btn" data-bs-dismiss="modal">Cancel</a>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="success_msg" id="otp-success-message" style="display: none;">
                                    <p>                        
                                        OTP sent to your registered mobile number.                    
                                    </p>
                                </div>
                                <div class="error_msg" id="otp-error-message" style="display: none;">
                                    <p>                        
                                        Please enter correct password
                                    </p>
                                </div>
                            </div>

                            <div class="otp-container" style="display: none;">
                                <div class="col-md-12">
                                    <label>OTP</label>
                                    <div class="form-group">
                                        <input id="otp" name="otp" type="text" placeholder="XXXXXX" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="success_msg" id="otp-confirm-success-message" style="display: none;">
                                        <p></p>
                                    </div>
                                    <div class="error_msg" id="otp-confirm-error-message" style="display: none;">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="col-md-12 text-center">
                                    <div class="view_result">
                                        <a href="javascript:void(0);" id="confirm_OTP" class="get_started btn blue-btn">Enable</a>
                                        <a href="javascript:void(0);" class="get_started cancel_btn dark-btn" data-dismiss="modal">Cancel</a>
                                    </div>
                                </div>
                            </div>
                            <div class="close-container" style="display: none;">
                                <div class="col-md-12 text-center">
                                    <div class="view_result">
                                        <a href="{{url('profile-settings')}}" class="get_started cancel_btn">Close</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection