{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')            
<section class="service-content_main faq-main bg-blue lastsection-space_b section-space">            
<div class="form_layout">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
                <div class="signup_box manage_profile">
                    @if( !Helpers::isWebview() )
                        <h2>FAQs</h2>
                    @endif
                    @foreach($faqs AS $faq)
                        @php
                            $ques_ans = $faq->ques_ans();
                        @endphp
                        <div class="content_box">
                            <h3>{{$faq->cat_name}}</h3>
                            @foreach($ques_ans AS $q_a)
                            <p>
                                <span>Q: {{$q_a->question}}</span>
                                <span>A: {{$q_a->answer}}</span>
                            </p>
                            @endforeach
                            
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</section>
    
@endsection