

{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           


    <section class="become-infu_main section-space bg-blue lastsection-space_b">
        <div class="become-infu_wrapper">
            <div class="container-md">
                <div class="become-infu_outer">
                    <div class="become-infu_inner">
                        <div class="become-infu_content text-center">
                            @if(!$user['category'])
                            <h3 class="become-infu-heading"> Apply To Be An Influencer </h3>
                            <p> Please verify your atleast one account with us. <br> You will be able to apply to the jobs. </p>
                           	@else
                           	<h3 class="become-infu-heading"> Link more social accounts </h3>
                            <p> You can link more accounts with your profile. <br> You will get more jobs. </p>
	                            @if(!empty($request))
	                            	@if($request['status'] == 0)
	                            		<p> Your request to become an influencer is <b>under review</b>.</p>
	                            	@elseif($request['status'] == 2)
	                            		<p> Your request to become an influencer is <b>rejected</b> by admin. Please contact to admin.</p>
	                            	@endif	
	                            @endif
                           	@endif
                        </div>
                        <form class="verify-form" action="{{ url('verify-details') }}" id="verify-form" method="post">
                        @if(!$user['category'])
                        	@csrf

                        	<div class="verify-social_media">

                                @if(Helpers::platformCategorey(\App\Models\SocialPlatform::INSTAGRAM))
                                <div class="verify-media_box">
                                    <input type="radio" id="radio1" name="platform" value="{{\App\Models\SocialPlatform::INSTAGRAM}}">
                                    <label for="radio1">
                                        <img class="getPlatformId" src="{{ Helpers::asset('images/social-v1.png') }}" alt="" data-id="{{\App\Models\SocialPlatform::INSTAGRAM}}">
                                        <span class="verify-text getPlatformId" data-id="{{\App\Models\SocialPlatform::INSTAGRAM}}"> Verify your account </span>
                                    </label>
                                </div>
                                @endif
                                @if(Helpers::platformCategorey(\App\Models\SocialPlatform::FACEBOOK))
                                <div class="verify-media_box">

                                    <input type="radio" id="radio2" name="platform" value="{{\App\Models\SocialPlatform::FACEBOOK}}">
                                    <label for="radio2">  
                                        <img class="getPlatformId" src="{{ Helpers::asset('images/social-v2.png') }}" alt="" data-id="{{\App\Models\SocialPlatform::FACEBOOK}}">
                                        <span class="verify-text getPlatformId" data-id="{{\App\Models\SocialPlatform::FACEBOOK}}"> Verify your account </span>
                                    </label>
                                </div>
                               @endif
                                @if(Helpers::platformCategorey(\App\Models\SocialPlatform::YOUTUBE))
                                <div class="verify-media_box">
                                    <input type="radio" id="radio3" name="platform" value="{{\App\Models\SocialPlatform::YOUTUBE}}">
                                    <label for="radio3"> 
                                        <img class="getPlatformId" src="{{ Helpers::asset('images/social-v3.png') }}" alt="" data-id="{{\App\Models\SocialPlatform::YOUTUBE}}">
                                        <span class="verify-text getPlatformId" data-id="{{\App\Models\SocialPlatform::YOUTUBE}}"> Verify your account </span>
                                    </label>

                                </div>
                                @endif
                                @if(Helpers::platformCategorey(\App\Models\SocialPlatform::TIKTOK))
                                <div class="verify-media_box">
                                    <input type="radio" id="radio4" name="platform" value="{{\App\Models\SocialPlatform::TIKTOK}}">
                                    <label for="radio4"> 
                                        <img class="getPlatformId" src="{{ Helpers::asset('images/social-v4.png') }}" alt="" data-id="{{\App\Models\SocialPlatform::TIKTOK}}" >
                                        <span class="verify-text getPlatformId" data-id="{{\App\Models\SocialPlatform::TIKTOK}}"> Verify your account </span>
                                    </label>
                                </div>
                                 @endif
                                @if(Helpers::platformCategorey(\App\Models\SocialPlatform::TWITTER))
                                <div class="verify-media_box">
                                    <input type="radio" id="radio5" name="platform" value="{{\App\Models\SocialPlatform::TWITTER}}">
                                    <label for="radio5"> 
                                        <img class="getPlatformId" src="{{ Helpers::asset('images/social-v5.png') }}" alt=""  data-id="{{\App\Models\SocialPlatform::TWITTER}}">
                                        <span class="verify-text getPlatformId"  data-id="{{\App\Models\SocialPlatform::TWITTER}}"> Verify your account </span>
                                    </label>
                                </div>
                                @endif
                        	</div>

	                        <div class="category-select">
	                            <span class="select-arrow_span"> 
                                    <select class="platformsCat" name="category"> 

		                                @foreach($categories as $key => $category)
		                                	<option value="{{$key}}"> {{ $category }} </option>
		                                @endforeach
		                            </select>
		                        </span>
	                            <div class="submit-btn">
	                                <input id="verify-form-submit" type="submit" value="Submit" class="flex-center">
	                            </div>
	                        </div>
                        @else

                            <div class="verify-social_media">
	                            <div class="verify-media_box">
	                                <a href="{{ url('verify/instagram') }}"> 
	                                	<img src="{{ Helpers::asset('images/social-v1.png') }}" alt="">
	                                    <span class="verify-text">{{ in_array(\App\Models\SocialPlatform::INSTAGRAM, $platforms ) ? "Your Acount has bean Verified" : 'Verify your account' }}</span>
                                    </a>
	                            </div>
	                            <div class="verify-media_box">
	                                <a href="{{ url('verify/facebook') }}"> 
	                                	<img src="{{ Helpers::asset('images/social-v2.png') }}" alt="">
	                                    <span class="verify-text">{{ in_array(\App\Models\SocialPlatform::FACEBOOK, $platforms ) ? "Your Acount has been Verified" : 'Verify your account' }}</span>
                                    </a>
	                            </div>
	                            <div class="verify-media_box">
	                                <a href="{{ url('verify/youtube') }}"> 
	                                	<img src="{{ Helpers::asset('images/social-v3.png') }}" alt="">
	                                    <span class="verify-text">{{ in_array(\App\Models\SocialPlatform::YOUTUBE, $platforms ) ? "Your Acount has been Verified" : 'Verify your account' }}</span>
                                    </a>
	                            </div>
	                            <div class="verify-media_box">
	                                <a href="{{ url('verify/tiktok') }}"> 
	                                	<img src="{{ Helpers::asset('images/social-v4.png') }}" alt="">
	                                    <span class="verify-text">{{ in_array(\App\Models\SocialPlatform::TIKTOK, $platforms ) ? "Your Acount has been Verified" : 'Verify your account' }}</span>
                                    </a>
	                            </div>
	                            <div class="verify-media_box">
	                                <a href="{{ url('verify/twitter') }}"> 
	                                	<img src="{{ Helpers::asset('images/social-v5.png') }}" alt="">
	                                    <span class="verify-text">{{ in_array(\App\Models\SocialPlatform::TWITTER, $platforms ) ? "Your Acount has been Verified" : 'Verify your account' }}</span>
                                    </a>
	                            </div>
	                        </div>
                        @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection