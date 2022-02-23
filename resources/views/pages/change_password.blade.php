{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           
    <div class="form_layout">
        <div class="container">
            <div class="row">
                @include('user-flash-message')
                <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
                    <div class="signup_box">
                        <h2>Change Password</h2>
                        <form action={{url('change-password')}} method="POST" id="change-password-form" autocomplete="off">
                            @csrf
                            <div class="signup_form login_form password_block change_password_block">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Current Password</label>
                                            <input type="password" name="current_password" placeholder="************" class="form-control">
                                            <div class="view_password"><i class="fa fa-eye" aria-hidden="true"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input id="password" type="password" name="new_password" placeholder="************" class="form-control">
                                            <div class="view_password"><i class="fa fa-eye" aria-hidden="true"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" name="confirm_password" placeholder="************" class="form-control">
                                            <div class="view_password"><i class="fa fa-eye" aria-hidden="true"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <input class="get_started" id="change-password-submit" type="submit" value="Save" >
                                <!-- <a href="" class="get_started" id="change-password-submit">Save</a> -->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection