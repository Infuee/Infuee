{{-- Extends layout --}}
@extends('admin.auth.layouts.auth')

{{-- Content --}}
@section('content')           


            <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
                <div class="login-aside d-flex flex-row-auto bgi-size-cover bgi-no-repeat p-10 p-lg-10" style="background-image: url(/media/bg/bg-4.jpg);">
                    <div class="d-flex flex-row-fluid flex-column justify-content-between">
                        <a href="#" class="flex-column-auto mt-5 pb-lg-0 pb-10">
                            <img src="/media/logos/logo.png" class="max-h-70px" alt="" />
                        </a>
                        <div class="flex-column-fluid d-flex flex-column justify-content-center">
                            <h3 class="font-size-h1 mb-5 text-white">Welcome to {{env('APP_NAME')}}!</h3>
                            <!-- <p class="font-weight-lighter text-white opacity-80">The ultimate Bootstrap, Angular 8, React &amp; VueJS admin theme framework for next generation web apps.</p> -->
                        </div>
                        <div class="d-none flex-column-auto d-lg-flex justify-content-between mt-10">
                            <div class="opacity-70 font-weight-bold text-white">© {{date('Y')}} {{env('APP_NAME')}}</div>
                            <!-- <div class="d-flex">
                                <a href="#" class="text-white">Privacy</a>
                                <a href="#" class="text-white ml-10">Legal</a>
                                <a href="#" class="text-white ml-10">Contact</a>
                            </div> -->
                        </div>
                    </div>
                </div>
                
                <div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
                    <!-- <div class="position-absolute top-0 right-0 text-right mt-5 mb-15 mb-lg-0 flex-column-auto justify-content-center py-5 px-10">
                        <span class="font-weight-bold text-dark-50">Dont have an account yet?</span>
                        <a href="javascript:;" class="font-weight-bold ml-2" id="kt_login_signup">Sign Up!</a>
                    </div> -->

                    <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
                        <div class="login-form login-signin">
                            <div class="text-center mb-10 mb-lg-20">
                                <h3 class="font-size-h1">Sign In</h3>
                                <p class="text-muted font-weight-bold">Enter your email and password</p>
                                @include('flash-message')
                            </div>
                            <form class="form" novalidate="novalidate" id="kt_login_signin_form" action="{{url('admin/login')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control form-control-solid h-auto py-5 px-6" type="text" placeholder="Email" name="username" autocomplete="off" value="{{old('username')}}" />
                                    @if ($errors->has('username'))
                                    <span class="invalid-feedback has-error" role="alert">
                                        <span>{{ $errors->first('username') }}</span>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Password" name="password" autocomplete="off" />
                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback has-error" role="alert">
                                        <span>{{ $errors->first('password') }}</span>
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                                    <a href="javascript:;" class="text-dark-50 text-hover-primary my-3 mr-2" id="kt_login_forgot">Forgot Password ?</a>
                                    <input type="submit" name="" id="kt_login_signin_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3" value="{{ __('Sign In') }}" />
                                </div>
                            </form>
                        </div>
                        <div class="login-form login-signup">
                            <div class="text-center mb-10 mb-lg-20">
                                <h3 class="font-size-h1">Sign Up</h3>
                                <p class="text-muted font-weight-bold">Enter your details to create your account</p>
                            </div>
                            <form class="form" id="kt_login_signup_form">
                                <div class="form-group">
                                    <input class="form-control form-control-solid h-auto py-5 px-6" type="text" placeholder="Fullname" name="fullname" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-solid h-auto py-5 px-6" type="email" placeholder="Email" name="email" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Password" name="password" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Confirm password" name="cpassword" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <label class="checkbox mb-0">
                                    <input type="checkbox" name="agree" />
                                    <span></span>I Agree the
                                    <a href="#">terms and conditions</a></label>
                                </div>
                                <div class="form-group d-flex flex-wrap flex-center">
                                    <input type="submit" name="" id="kt_login_signup_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4" value="{{ __('Submit') }}" />
                                    <button type="button" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-4">Cancel</button>
                                </div>
                            </form>
                        </div>
                        <div class="login-form login-forgot">
                            <div class="text-center mb-10 mb-lg-20">
                                <h3 class="font-size-h1">Forgotten Password ?</h3>
                                <p class="text-muted font-weight-bold">Enter your email to reset your password</p>
                            </div>
                            <form class="form" novalidate="novalidate" id="kt_login_forgot_form" method="post" action="{{url('admin/forgotpassword')}}">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control form-control-solid h-auto py-5 px-6" type="email" placeholder="Email" name="email" autocomplete="off" />
                                </div>
                                <div class="form-group d-flex flex-wrap flex-center">
                                    <input type="submit" name="" id="kt_login_forgot_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3 mx-4" value="{{ __('Submit') }}" />
                                    <button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bold px-9 py-4 my-3 mx-4">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="d-flex d-lg-none flex-column-auto flex-column flex-sm-row justify-content-between align-items-center mt-5 p-5">
                        <div class="text-dark-50 font-weight-bold order-2 order-sm-1 my-2">© 2020 Metronic</div>
                        <div class="d-flex order-1 order-sm-2 my-2">
                            <a href="#" class="text-dark-75 text-hover-primary">Privacy</a>
                            <a href="#" class="text-dark-75 text-hover-primary ml-4">Legal</a>
                            <a href="#" class="text-dark-75 text-hover-primary ml-4">Contact</a>
                        </div>
                    </div>
                </div>
            </div>
@endsection