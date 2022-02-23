{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')
<section class="contact-main bg-blue lastsection-space_b section-space">   
<div class="form_layout">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-lg-7 mx-auto">
                @include('user-flash-message')
                <div class="signup_box">
                    @if( !Helpers::isWebview() )
                        <h2>Contact Us</h2>
                    @endif
                    <form action="{{url('contact-us')}}" method="post" id="contact-us-form" class="common-form">
                        @csrf
                        <div class="signup_form login_form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group common-form_group">
                                        <input type="text" placeholder="Full Name" name="full_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group common-form_group">
                                        <input type="email" placeholder="Email Address" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group common-form_group">
                                        <textarea placeholder="What are you contacting us about?" name="desc" class="form-control textarea_box" rows="6"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center send-btn_outer mt-5">
                            <input type="submit" id="contact-us-form-submit" class="btn send-btn" name="" value="Send Request"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
