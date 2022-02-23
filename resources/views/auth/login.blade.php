{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           


<section class="login-wrapper lastsection-space_b bg-blue section-space">

    <div class="container">
        <div class="login-outer">
            <div class="login-inner">
                <div class="login-content">
                <h3 class="login-heading"> Login </h3>
                    @if(@$user)
                        <h3 class="login-heading"> Verify OTP </h3>
                        <form class="form-main" action="{{url('login')}}" method="post" id="user_login_otp_form">
                            @csrf
                            @if(isset($beInfluencer))
                                <input type="hidden" name="beInfluencer" value="1">
                            @endif
                            <div class="form-out_c">
                                <input id="otp" name="otp" placeholder="XXXXXX" type="number" maxlength="6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" placeholder="Password" class="form-control">
                                <input name="email" type="hidden" value="{{@$user['email']}}">
                                @if(@$error)
                                    <div class="fv-plugins-message-container"><div data-field="otp" data-validator="notEmpty" class="fv-help-block">{{@$error}}</div></div>
                                @endif
                            </div>
                            <div class="login-btn_outer">

                                <input type="submit" id="user_login_otp_submit" class="get_started btn login-btn flex-center" name="verify" value="Let’s go"/>
                                <input type="submit" id="user_login_resent_submit" class="get_started btn login-btn flex-center" name="resend" value="Re-Send OTP"/>
                                @if(@$error)
                                    <div class="fv-plugins-message-container"><div data-field="otp" data-validator="notEmpty" class="fv-help-block">{{@$error}}</div></div>
                                @endif
                            </div>
                        </form>
                    @else


                    <form class="form-main" action="{{url('login')}}" method="post" id="user_login_page">
                        @csrf
                        @if(isset($beInfluencer))
                            <input type="hidden" name="beInfluencer" value="1">
                        @endif
                        <div class="form-out_c">
                            <input name="email" type="email" class="form-control" placeholder="Email  Address" aria-describedby="emailHelp" value="{{old('email')}}">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="form-out_c">
                            <input id="password" name="password" type="password" class="form-control" class="form-control" placeholder="Password">
                            <i class="fas fa-unlock-alt"></i>
                        </div>
                        <div class="login-btn_outer">
                            <input type="submit" id="user_login_submit" class="btn login-btn flex-center" name="" value="Login"/>
                            <a href="{{url('forgot-password')}}" class="forgot_link"> Forgot Password? </a>
                        </div>
                    </form>


                    @endif

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