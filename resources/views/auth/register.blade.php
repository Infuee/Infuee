{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           


    <section class="login-wrapper signup-wrapper lastsection-space_b bg-blue section-space">

        <div class="container">
            <div class="login-outer">
                <div class="login-inner">
                    <div class="login-content">
                        <h3 class="login-heading"> Sign up </h3>
                        <form class="form-main" action="{{url('register')}}" method="post" id="user-registration-from" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-out_c">
                                        <input type="text" class="form-control" placeholder="Name" maxlength="50"  onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))' name="first_name" value="{{old('first_name')}}">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-out_c">
                                        <input id="email" type="email" name="email"  class="form-control" placeholder="Email Address" value="{{old('email')}}">
                                        <i class="fas fa-envelope"></i>
                                        @if ($errors->has('email'))
                                        <div class="fv-plugins-message-container">
                                            <div id="email-error" data-field="email" data-validator="notEmpty" class="fv-help-block">
                                                {{ $errors->first('email') }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-out_c">
                                        <input type="email" class="form-control" placeholder="Confirm Email Address" name="cemail" value="{{old('cemail')}}">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                </div>
                                <div class="col-4 col-sm-3 pe-0">
                                    <div class="form-out_c">
                                        <span class="select-arrow_span">
                                            <select class="form-control" name="country_code">
                                                @foreach($countries as $country)
                                                    <option value="{{$country->id}}" {{old('country_code') == $country->id ? 'selected':''}}>+{{$country->code}} [{{$country['name']}}]</option>
                                                @endforeach
                                            </select>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-8 col-sm-9">
                                    <div class="form-out_c">
                                        <input type="number" name="phone" class="form-control" min="0" placeholder="Phone Number"  maxlength="10" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="{{old('phone')}}">

                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-out_c">
                                        <input id="address" name="address" type="text" class="form-control" placeholder="Address" value="{{old('address')}}">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <input type="hidden" name="city" id="city" value="{{old('city')}}">
                                        <input type="hidden" name="state" id="state" value="{{old('state')}}">
                                        <input type="hidden" name="country" id="country" value="{{old('country')}}">
                                        <input type="hidden" name="lat" id="lat" value="{{old('lat')}}">
                                        <input type="hidden" name="lng" id="lng" value="{{old('lng')}}">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-out_c">
                                        <input type="text" id="date-of-birth" class="form-control" name="dob" placeholder="Date of birth" value="{{old('dob')}}">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-out_c">
                                        <input type="text" class="form-control" placeholder="School, High School, College (Optional)" name="school" value="{{old('school')}}">
                                        <i class="fas fa-school"></i>
                                    </div>
                                </div>
                                <div class="col-4 col-12">
                                    <div class="form-out_c">
                                        <span class="select-arrow_span">
                                            <select class="form-control" name="race_id">
                                                <option value="">Select Race </option>
                                                @foreach($race as $row)
                                                    <option value="{{$row->id}}">{{$row->title}} </option>
                                                @endforeach
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-out_c">

                                        <input type="password" name="password" class="form-control" class="form-control" placeholder="Password" id="password" name="password" value="">
                                        <i class="fas fa-unlock-alt"></i>
                                        <span class="field-icon"><i toggle="#password"
                                                class="fas fa-eye toggle-password"></i> </span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-out_c">
                                        <input type="password" name="cpassword" class="form-control" placeholder="Confirm Password" id="password-field2" name="password" value="">
                                        <i class="fas fa-unlock-alt"></i>
                                        <span class="field-icon"><i toggle="#password-field2"
                                                class="fas fa-eye toggle-password"></i> </span>
                                    </div>
                                </div>
                            </div>
                            <div class="login-btn_outer mt-4">
                                <input type="submit" class="btn login-btn flex-center" name="" value="Sign Up" id="user-registration-submit" />
                            </div>
                        </form>
                    </div>


                </div>
                <div class="for-signup_outer flex-center">
                    <p>Already have an account? <a href="{{url('/login')}}" class="signup-btn flex-center">LOGIN</a></p>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection