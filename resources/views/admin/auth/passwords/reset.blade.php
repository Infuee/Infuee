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
                            <p class="font-weight-lighter text-white opacity-80">The ultimate Bootstrap, Angular 8, React &amp; VueJS admin theme framework for next generation web apps.</p>
                        </div>
                        <div class="d-none flex-column-auto d-lg-flex justify-content-between mt-10">
                            <div class="opacity-70 font-weight-bold text-white">© {{date('Y')}} {{env('APP_NAME')}}</div>
                            <div class="d-flex">
                                <a href="#" class="text-white">Privacy</a>
                                <a href="#" class="text-white ml-10">Legal</a>
                                <a href="#" class="text-white ml-10">Contact</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
                    <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
                        <div class="login-form login-signin">
                            <div class="text-center mb-10 mb-lg-20">
                                <h3 class="font-size-h1">{{ __('Reset Password') }}</h3>
                                <p class="text-muted font-weight-bold">Enter your password and confirm password</p>
                            </div>
                            <form class="form" novalidate="novalidate" id="kt_password_form" action="{{url('admin/reset_password')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input class="form-control form-control-solid h-auto py-5 px-6" type="text" placeholder="Email" name="username" autocomplete="off" value="{{@$email}}" />
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Password" name="password" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Confirm Password" name="cpassword" autocomplete="off" />
                                </div>
                                <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                                    <input type="submit" name="" id="kt_password_submit" class="btn btn-primary font-weight-bold px-9 py-4 my-3" value="{{ __('Reset Password') }}" />
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