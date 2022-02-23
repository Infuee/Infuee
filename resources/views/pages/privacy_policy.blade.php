{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           
<section class="service-content_main bg-blue lastsection-space_b section-space">  
    <div class="form_layout">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
                	<div class="signup_box manage_profile content_box">
                    @php
                        $content = htmlspecialchars_decode(stripslashes(@$content->privacy_policy)); 
                        if( Helpers::isWebview() ){
                          $content = str_replace("PRIVACY POLICY", "",  $content) ;
                        }
                        echo $content ;                        
                    @endphp
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> 
@endsection