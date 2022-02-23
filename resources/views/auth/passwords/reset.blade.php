{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           


<section class="login-wrapper lastsection-space_b bg-blue section-space">
    <div class="container">
        <div class="login-outer">
            <div class="login-inner">
                <div class="login-content">
                <h3 class="login-heading"> {{ __('Reset Password') }} </h3>
                    <form class="form-main" novalidate="novalidate" id="user-registration-from" action="{{url('reset_password')}}" method="post"> 
                        @csrf
                            <div class="form-out_c">
                                <input type="email" name="email" placeholder="Email Address" class="form-control" value="{{$email}}" readonly="">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="form-out_c">
                                <input name="password" type="password" placeholder="Password" class="form-control" name="password" id="password">
                                <i class="fas fa-unlock-alt"></i>
                                <span class="field-icon"><i toggle="#password"
                                 class="fas fa-eye toggle-password"></i></span>
                            </div>
                            <div class="form-out_c">
                                <input type="password" name="cpassword"  placeholder="Confirm Password" name="cpassword" class="form-control" id="password-field2" value="">
                                <i class="fas fa-unlock-alt"></i>
                                <span class="field-icon"><i toggle="#password-field2"
                                                class="fas fa-eye toggle-password"></i> </span>
                                
                            </div>
                            <div class="login-btn_outer">
                                <input type="submit" class="get_started btn login-btn flex-center" name="" id="user-forgot-password-submit" value="Let’s go" />
                                <div class="login_link">
                                    <p>Remember Your Password? <a href="{{url('/login')}}">Login</a></p>
                                </div>
                            </div>
                    </form>
                    <div class="social-login">
                        <div class="social-login_outer">
                            <a href="{{ url('auth/instagram') }}"> <img src="{{ Helpers::asset('images/insta-icon.png')}}" alt=""> </a>
                            <a href="{{ url('auth/facebook') }}"> <img src="{{ Helpers::asset('images/fb-icon.png')}}" alt=""> </a>
                            <a href="{{ url('auth/youtube') }}"> <img src="{{ Helpers::asset('images/youtube-icon.png')}}" alt=""> </a>
                            <a href="{{ url('auth/twitter') }}"> <img src="{{ Helpers::asset('images/twitter-icon.png')}}" alt=""> </a>
                            <a href="{{ url('auth/tiktok') }}"> <img src="{{ Helpers::asset('images/tiktok-icon.png')}}" alt=""> </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="for-signup_outer flex-center">
                <a href="javascript:;" class="no-account_link"> Don't have an  Account? </a>
                <a href="{{url('signup')}}" class="signup-btn flex-center">  SignUp </a>
            </div>
        </div>
    </div>
    </div>
</section>

@endsection
