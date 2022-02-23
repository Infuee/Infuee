{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           


<section class="login-wrapper lastsection-space_b bg-blue section-space">

    <div class="container">
        <div class="login-outer">
            <div class="login-inner">
                <div class="login-content">
                <h2 class="login-heading">Forgot Password</h2>
                <p class="subtitle_forgot text-center">No worries! Enter your email below and we’ll send you a reset link.</p>


                    <form class="form-main" action="{{url('forgot-password')}}" method="post" id="forgot-password-form">
                        @csrf
                        <div class="form-out_c">
                            <input type="email" name="email" placeholder="Email Address" class="form-control" value="{{old('email')}}">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="login-btn_outer">
                            <input type="submit" id="user_login_submit" class="btn login-btn flex-center" name="" value="Send Link"/>
                            
                        </div>
                    </form>

                </div>


            </div>
            <div class="for-signup_outer flex-center">
                <a href="javascript:;" class="no-account_link"> Already have Account? </a>
                <a href="{{url('login')}}" class="signup-btn flex-center"> Login </a>
            </div>
        </div>
    </div>
    </div>
</section>


@endsection