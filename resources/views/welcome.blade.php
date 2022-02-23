{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 
@php
    $content =  @$content->home_page; 
@endphp


    <section class="home-banner_wrapper">
        <div class="home-banner_outer">
            <div class="container">
                <div class="home-banner_inner">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="banner-content">
                                @php
                                    echo htmlspecialchars_decode(stripslashes(@$home_page_top_section->home_page_top_section)); 
                                @endphp

                                <div class="banner-get_btn">
                                    <a href="{{ url('influencers') }}" class="btn get_btn"> Get Started </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-3">
                            <div class="banner-img">
                                <img src="{{ Helpers::asset('images/banner-logo.png') }}" alt="" class="w-100">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="category-wrapper section-space">
        <div class="category-outer">
            <div class="container">
                <div class="common-heading text-center">
                    <h2> Categories </h2>
                </div>

                <div class="category-inner">

                    <div class="social-influencer-row">
                        @if(@$INSTAGRAM->status=='1')
                        <div class="social-influencer-col">
                            <div class="social-inner">
                                <a href="{{ url('influencers/instagram') }}" class="social-img">
                                    <img src="{{ Helpers::asset('images/social-icon1.png') }}" alt="">
                                </a>
                                <p> Instagram Influencer </p>
                            </div>
                        </div>
                        @endif
                        @if(@$FACEBOOK->status=='1')
                        <div class="social-influencer-col">
                            <div class="social-inner">
                                <a href="{{ url('influencers/facebook') }}" class="social-img">
                                    <img src="{{ Helpers::asset('images/social-icon2.png') }}" alt="">
                                </a>
                                <p> Facebook Influencer </p>
                            </div>
                        </div>
                        @endif
                        @if(@$YOUTUBE->status=='1')
                        <div class="social-influencer-col">
                            <div class="social-inner">
                                <a href="{{ url('influencers/youtube') }}" class="social-img">
                                    <img src="{{ Helpers::asset('images/social-icon3.png') }}" alt="">
                                </a>
                                <p> Youtube Influencer </p>
                            </div>
                        </div>
                        @endif
                        @if(@$TIKTOK->status=='1')
                        <div class="social-influencer-col">
                            <div class="social-inner">
                                <a href="{{ url('influencers/tiktok') }}" class="social-img">
                                    <img src="{{ Helpers::asset('images/social-icon4.png') }}" alt="">
                                </a>
                                <p> Tiktok Influencer </p>
                            </div>
                        </div>
                        @endif
                        @if(@$TWITTER->status=='1')
                        <div class="social-influencer-col">
                            <div class="social-inner">
                                <a href="{{ url('influencers/twitter') }}" class="social-img">
                                    <img src="{{ Helpers::asset('images/social-icon5.png') }}" alt="">
                                </a>
                                <p> Twitter Influencer </p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="category-content">
                        <div class="row">
                            <div class="col-12 col-md-7 col-center-align">
                                <div class="category-logo">
                                    <img src="{{ Helpers::asset('images/category-logo.png') }}" alt="" class="w-100">
                                </div>
                            </div>
                            <div class="col-12 col-md-5 col-center-align">
                                <div class="about-question">
                                    @php
                                         echo htmlspecialchars_decode(stripslashes(@$home_page_middle_section->home_page_middle_section)); 
                                    @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <section class="top-influencers-wrapper section-space">
        <div class="top-influencers_outer">
            <div class="common-heading text-center">
                <h2> Top Social Media Influencers </h2>
            </div>
            <div class="container">
                <div class="top-influencers_inner">
                    <div class="row slick">
                        @if(@$TIKTOK->status=='1')
                        <div class="col-4 slide-item">
                            <!-- <div class="row">
                                <div class="col-4"></div>
                                <div class="col-4"></div>
                                <div class="col-4"></div>
                                <div class="col-6"></div>
                                <div class="col-6"></div>
                            </div> -->
                        <ul class="slide-inner-images">
                                @if(count($TopTiktok)) 
                                @foreach($TopTiktok as $Toptiktok)
                                <li>
                                    @if(!empty($Toptiktok->image))
                                    <a href="{{ url('influencer/tiktok') }}"> <img src="{{ Helpers::asset('media/users/'.@$Toptiktok->image) }}" alt="" class="portrait-img">
                                    </a>
                                    @else
                                    <a href="{{ url('influencer/tiktok') }}"> <img src="{{ Helpers::asset('media/users/1609736159.jpeg') }}" alt="" class="portrait-img"></a>
                                    @endif
                                </li>
                                @endforeach
                                @else
                                <a  href="{{ url('influencer/tiktok') }}"> <img src="{{ Helpers::asset('media/users/tiktok-placeholder.png') }}" alt="" class="portrait-img placeholder-img"></a>     
                                @endif
                        </ul>

                            <div class="text-center"> <a  href="{{ url('influencer/tiktok') }}" class="identification"> Tiktokers </a>
                            </div>
                        </div>
                        @endif
                        @if(@$INSTAGRAM->status=='1')
                        <div class="col-4 slide-item">
                        <ul class="slide-inner-images">
                                @if(count($TopInstagramer)) 
                                @foreach($TopInstagramer as $Topinstagramer)
                                <li>
                                    @if(!empty($Topinstagramer->image))
                                    <a href="{{ url('influencer/instagram') }}"> 
                                        <img src="{{ Helpers::asset('media/users/'.@$Topinstagramer->image) }}" alt="" class="portrait-img">
                                    </a>
                                    @else
                                    <a href="{{ url('influencer/instagram') }}"> 
                                        <img src="{{ Helpers::asset('media/users/1609736159.jpeg') }}" alt="" class="portrait-img">
                                    </a>
                                    @endif
                                </li>
                                @endforeach
                                @else
                                <a href="{{ url('influencer/instagram') }}"> 
                                    <img src="{{ Helpers::asset('media/users/insta-placeholder.png') }}" alt="" class="portrait-img placeholder-img">
                                </a>     
                                @endif
                        </ul>
                            <div class="text-center"> <a href="{{ url('influencer/instagram') }}" class="identification"> Instagrammers </a>
                            </div>

                        </div>
                         @endif
                         @if(@$YOUTUBE->status=='1')
                        <div class="col-4 slide-item">
                            <ul class="slide-inner-images">
                                @if(count($TopYouTuber)) 
                                @foreach($TopYouTuber as $Youtubers)
                                <li>
                                    @if(!empty($Youtubers->image))
                                    <a href="{{ url('influencer/youtube') }}"> <img src="{{ Helpers::asset('media/users/'.@$Youtubers->image) }}" alt="" class="portrait-img">
                                    </a>
                                     @else
                                     <a href="{{ url('influencer/youtube') }}"> <img src="{{ Helpers::asset('media/users/1609736159.jpeg') }}" alt="" class="portrait-img"></a>
                                     @endif
                                </li>
                                @endforeach
                                @else
                                <a href="{{ url('influencer/youtube') }}"> <img src="{{ Helpers::asset('media/users/youtube-placeholder.png') }}" alt="" class="portrait-img placeholder-img"></a>     
                                @endif
                               
                            </ul>
                            <div class="text-center"> <a href="{{ url('influencer/youtube') }}" class="identification"> Youtubers </a>
                            </div>

                        </div>
                        @endif
                        @if(@$FACEBOOK->status=='1')
                        <div class="col-4 slide-item">
                             <ul class="slide-inner-images">
                                 @if(count($TopFacebook)) 
                                @foreach($TopFacebook as $Topfacebook)
                                <li>
                                    @if(!empty($Topfacebook->image))
                                    <a href="{{ url('influencer/facebook') }}"> <img src="{{ Helpers::asset('media/users/'.@$Topfacebook->image) }}" alt="" class="portrait-img">
                                    </a>
                                    @else
                                    <a href="{{ url('influencer/facebook') }}"> <img src="{{ Helpers::asset('media/users/1609736159.jpeg') }}" alt="" class="portrait-img"></a>
                                    @endif
                                </li>
                                @endforeach
                                @else
                                <a href="{{ url('influencer/facebook') }}"> <img src="{{ Helpers::asset('media/users/facebook-placeholder.png') }}" alt="" class="portrait-img placeholder-img"></a>     
                                @endif
                            </ul>
                            <div class="text-center"> <a href="{{ url('influencer/facebook') }}" class="identification">Facebookers </a>
                            </div>

                        </div>
                        @endif
                        @if(@$TWITTER->status=='1')
                        <div class="col-4 slide-item">
                           <ul class="slide-inner-images"> 
                            @if(count($TopTwitter)) 
                                @foreach($TopTwitter as $Toptwitter)
                                <li>
                                    @if(!empty($Toptwitter->image))
                                    <a href="{{ url('influencer/twitter') }}"> <img src="{{ Helpers::asset('media/users/'.@$Toptwitter->image) }}" alt="" class="portrait-img">
                                    </a>
                                    @else
                                    <a href="{{ url('influencer/twitter') }}"> <img src="{{ Helpers::asset('media/users/1609736159.jpeg') }}" alt="" class="portrait-img"></a>
                                    @endif
                                </li>
                                @endforeach
                                @else
                                <a href="{{ url('influencer/twitter') }}"> <img src="{{ Helpers::asset('media/users/twitter-placeholder.png') }}" alt="" class="portrait-img placeholder-img ab"></a>     
                                @endif
                            </ul>
                            <div class="text-center"> <a href="{{ url('influencer/twitter') }}" class="identification">Twitters </a>
                            </div>

                        </div>
                        @endif
                    </div>
                    <button class="left-arrow" aria-label="Next" type="button"><i
                            class="fas fa-long-arrow-alt-left"></i></button>
                    <button class="right-arrow" aria-label="Previous" type="button"><i
                            class="fas fa-long-arrow-alt-right"></i></button>
                </div>
            </div>
        </div>

    </section>



    <section class="testimonial-wrapper bg-blue section-space">
        <div class="testimonial-outer">

            <div class="container">
                <div class="common-heading">
                    <h2> Testimonial </h2>
                </div>
                <div class="testimonial-inner">
                    <div class="single-slick">
                        @if(count($usersTestimonial) > 0)

                        @foreach($usersTestimonial as $usersTestimonials)
                        <div>
                            <div class="row flip-col">
                                <div class="col-12 col-md-12 col-lg-6">
                                    <div class="testimonial-content">

                                        <p> <img src="{{ Helpers::asset('images/Forma1.png') }}" alt="" class="form1">
                                            <i> {{ $usersTestimonials->description}}</i>
                                            <img src="{{ Helpers::asset('images/Forma2.png') }}" alt="" class="form2">
                                        </p>
                                        <p class="name"> {{ $usersTestimonials->first_name}} </p>

                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-lg-6">
                                    <div class="testimonial-img-div">
                                        <div class="testimonial-back"> </div>
                                        <div class="testimonial-image">
                                            @if(empty($usersTestimonials->image))
                                            <img src="{{ Helpers::asset('images/testimonial1.jpg') }}" alt="">
                                            @else
                                            <img src="{{ url('media/users'.'/'.$usersTestimonials->image) }}" alt="">
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @else
                            <h3>No Testimonial!!!</h3>
                        @endif

                    </div>


                    <button class="prev-arrow" aria-label="Next" type="button"><i
                            class="fas fa-long-arrow-alt-left"></i></button>
                    <button class="next-arrow" aria-label="Previous" type="button"><i
                            class="fas fa-long-arrow-alt-right"></i></button>

                </div>
            </div>
        </div>
    </section>


@endsection



