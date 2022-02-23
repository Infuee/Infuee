{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           

    <section class="profile-banner"></section>

    <section class="profile-section bg-blue">
        <div class="profile-outer">
            <div class="container">
                <div class="profile-inner">
                    <div class="row">
                        <div class="col-md-12 col-lg-9 col-xl-8">
                            <div class="profile-detail">
                                <div class="profile-img">
                                    @if(is_file(public_path("/media/users").'/'.@$user['image']))
                                        <img src="{{ Helpers::asset('media/users/'.$user['image'])}}">
                                    @else
                                        <img src="{{ Helpers::asset('media/users/blank.png') }}">
                                    @endif
                                </div>
                                <div class="profile-content">
                                    <div class="profile-inline">
                                        @if($image = @$user->getCountryFlag())
                                            <img class="flag-img" src="{{ Helpers::asset('media/country_flag/'.$image) }}"/>
                                        @else
                                            <i class="fa fa-flag"></i>  
                                        @endif
                                        <div class="position-relative me-3 d-inline">
                                            <h4> {{@$user['first_name'].' '.@$user['last_name']}} </h4>
                                            <img class="position-absolute" src="{{ Helpers::asset('images/verify-icon.png') }}" alt="verify">
                                        </div>

                                        @php
                                            $rating = Helpers::avgRateing($user['id']);
                                                $rating['totalRating'];
                                        @endphp
                                        <span class="review-star">
                                           @include('influencer.job.common',$rating) 
                                            <span> {{$rating['average']}}* ({{$rating['total_users']}} User) 
                                            </span>
                                        </span>
                                        <p class="skin-txt_m"> {{@$user->getCategory()}} </p>
                                        <div class="hire-outer mobile-hire_btn">
                                            <a href="{{ url('message/0/'. Helpers::encrypt($user['id']) ) }}"><img src="{{ Helpers::asset('images/conversation1.png') }}" alt="Conversation" class="conversation-btn"> </a>
                                        </div>

                                    </div>
                                    <p class="skin-txt_d"> {{@$user->getCategory()}} </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-3 col-xl-4">
                            
                            @if(@$authUser)
                               <div class="hire-outer desktop-hire_btn mt-3 mt-sm-0">
                                <a href="{{url('profile-settings')}}" class="btn btn-light"><i class="far fa-edit"></i> Edit Account Info</a>
                                <a href="{{url('plan-setting')}}" class="btn btn-light"> Manage Plans </a>
                            </div>
                            @else
                           <div class="hire-outer desktop-hire_btn mt-3 mt-sm-0">
                                <a href="{{ url('message/0/'. Helpers::encrypt($user['id']) ) }}" class="convo-link"><img src="{{ Helpers::asset('images/conversation1.png') }}" alt="Conversation" class="conversation-btn"> </a>

                                <a href="{{ url('hire-influencer/'. Helpers::encrypt(@$user['id']) ) }}" class="hire-btn btn btn-light"> Hire </a>

                            </div>
                            @endif

                        </div>
                    </div>

                    <div class="social-account_outer">
                        @if(in_array( \App\Models\SocialPlatform::INSTAGRAM , $platforms))
                        @php
                        $platform = $user->getPlatformDetails(\App\Models\SocialPlatform::INSTAGRAM);
                        @endphp
                        <div class="social-view_account">
                            <div class="social-media_profile">
                                <img src="{{ Helpers::asset('images/social-icon1.png') }}" alt="Instagram">
                            </div>
                            <div class="social-profile_content">
                                <p class="mb-2"> {{ @$platform['username'] }} </p>
                                <p class="mb-1"> {{ @$platform['followers']?:0 }} Followers</p>
                                <a href="https://www.instagram.com/{{ @$platform['username'] }}" class="view" target="_blank"> 
                                    View Account 
                                    <i class="fas fa-chevron-right" aria-hidden="true"></i> 
                                </a>
                            </div>
                        </div>
                        @endif
                        @if(in_array( \App\Models\SocialPlatform::FACEBOOK , $platforms))
                        @php
                        $platform = $user->getPlatformDetails(\App\Models\SocialPlatform::FACEBOOK);
                        @endphp
                        <div class="social-view_account">
                            <div class="social-media_profile">
                                <img src="{{ Helpers::asset('images/social-icon2.png') }}" alt="Facebook">
                            </div>
                            <div class="social-profile_content">
                                <p class="mb-2"> {{ $user['username'] }} </p>
                                <p class="mb-1"> {{ @$platform['followers']?:0 }} Followers</p>
                                <a href="https://www.facebook.com/profile.php?id={{ @$platform['platform_social_id'] }}" class="view" target="_blank"> View Account <i class="fas fa-chevron-right"
                                        aria-hidden="true"></i> </a>
                            </div>
                        </div>
                        @endif
                        @if(in_array( \App\Models\SocialPlatform::YOUTUBE , $platforms))
                        @php
                        $platform = $user->getPlatformDetails(\App\Models\SocialPlatform::YOUTUBE);
                        @endphp
                        <div class="social-view_account">
                            <div class="social-media_profile">
                                <img src="{{ Helpers::asset('images/social-icon3.png') }}" alt="Youtube">
                            </div>
                            <div class="social-profile_content">
                                <p class="mb-2"> {{ $user['username'] }} </p>
                                <p class="mb-1"> {{ @$platform['followers']?:0 }} Followers</p>
                                <a href="https://www.youtube.com/channel/{{ @$platform['platform_social_id'] }}" class="view"> View Account <i class="fas fa-chevron-right"
                                        aria-hidden="true"></i> </a>
                            </div>
                        </div>
                        @endif
                        @if(in_array( \App\Models\SocialPlatform::TIKTOK , $platforms))
                        @php
                        $platform = $user->getPlatformDetails(\App\Models\SocialPlatform::TIKTOK);
                        @endphp
                        <div class="social-view_account">
                            <div class="social-media_profile">
                                <img src="{{ Helpers::asset('images/social-icon4.png') }}" alt="Tiktok">
                            </div>
                            <div class="social-profile_content">
                                <p class="mb-2"> {{ $user['username'] }} </p>
                                <p class="mb-1"> {{ @$platform['followers']?:0 }} Followers</p>
                                <a href="javascript:;" class="view"> View Account <i class="fas fa-chevron-right"
                                        aria-hidden="true"></i> </a>
                            </div>
                        </div>
                        @endif
                        @if(in_array( \App\Models\SocialPlatform::TWITTER , $platforms))
                        @php
                        $platform = $user->getPlatformDetails(\App\Models\SocialPlatform::TWITTER);
                        @endphp
                        <div class="social-view_account">
                            <div class="social-media_profile">
                                <img src="{{ Helpers::asset('images/social-icon5.png') }}" alt="Twitter">
                            </div>
                            <div class="social-profile_content">
                                <p class="mb-2"> {{ $user['username'] }}  </p>
                                <p class="mb-1"> {{ @$platform['followers']?:0 }} Followers</p>
                                <a href="javascript:;" class="view"> View Account <i class="fas fa-chevron-right"
                                        aria-hidden="true"></i> </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    @if(Auth::check() && \Request::segment(1) == 'my-profile')
                    <form class="verify-form" action="{{ url('verify-details') }}" id="verify-form" method="post">
                        
                        <div class="verify-social_media">
                            @if(empty(in_array( \App\Models\SocialPlatform::INSTAGRAM , $platforms)))
                            <div class="verify-media_box">
                                <a href="{{ url('verify/instagram?type=my-profile') }}"> 
                                    <div class="social-media_profile">
                                        <img src="{{ Helpers::asset('images/social-v1.png') }}" alt="">
                                    </div>
                                    <span class="verify-text" style="color: white;">{{ in_array(\App\Models\SocialPlatform::INSTAGRAM, $platforms ) ? "Your Acount has bean Verified" : 'Verify your account' }}</span>
                                </a>
                            </div>
                            @endif
                           @if(empty(in_array( \App\Models\SocialPlatform::FACEBOOK , $platforms)))
                            <div class="verify-media_box">
                                <a href="{{ url('verify/facebook?type=my-profile') }}"> 
                                    <div class="social-media_profile">
                                        <img src="{{ Helpers::asset('images/social-v2.png') }}" alt="">
                                    </div>
                                    <span class="verify-text"style="color: white;">{{ in_array(\App\Models\SocialPlatform::FACEBOOK, $platforms ) ? "Your Acount has been Verified" : 'Verify your account' }}</span>
                                </a>
                            </div>
                            @endif
                            @if(empty(in_array( \App\Models\SocialPlatform::YOUTUBE , $platforms)))
                            <div class="verify-media_box">
                                <a href="{{ url('verify/youtube?type=my-profile') }}"> 
                                    <div class="social-media_profile">
                                        <img src="{{ Helpers::asset('images/social-v3.png') }}" alt="">
                                    </div>
                                    <span class="verify-text"style="color: white;">{{ in_array(\App\Models\SocialPlatform::YOUTUBE, $platforms ) ? "Your Acount has been Verified" : 'Verify your account' }}</span>
                                </a>
                            </div>
                            @endif
                            @if(empty(in_array( \App\Models\SocialPlatform::TIKTOK , $platforms)))
                            <div class="verify-media_box">
                                <a href="{{ url('verify/tiktok?type=my-profile') }}"> 
                                    <div class="social-media_profile">
                                        <img src="{{ Helpers::asset('images/social-v4.png') }}" alt="">
                                    </div>
                                    <span class="verify-text"style="color: white;">{{ in_array(\App\Models\SocialPlatform::TIKTOK, $platforms ) ? "Your Acount has been Verified" : 'Verify your account' }}</span>
                                </a>
                            </div>
                            @endif
                            @if(empty(in_array( \App\Models\SocialPlatform::TWITTER , $platforms)))
                            <div class="verify-media_box">
                                <a href="{{ url('verify/twitter?type=my-profile') }}"> 
                                    <div class="social-media_profile">
                                        <img src="{{ Helpers::asset('images/social-v5.png') }}" alt="">
                                    </div>
                                    <span class="verify-text"style="color: white;">{{ in_array(\App\Models\SocialPlatform::TWITTER, $platforms ) ? "Your Acount has been Verified" : 'Verify your account' }}</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </section>


    <section class="history-section bg-blue section-space">
        <div class="history-outer">
            <div class="container">
                <div class="common-heading agencies-heading gradient-border-100 text-center">
                    <h2> Work History </h2>
                </div>
                <div class="history_inner">
                    @if(!empty($WorkHistory))
                    @foreach($WorkHistory As $workhistory)
                    <div class="history-content">
                        <div>
                            <div class="image-wrapper">
                                @if(@$workhistory->image && is_file('media/users/'.$workhistory->image))
                                <img src="{{ Helpers::asset('media/users/'.$workhistory->image) }}" alt="" width="50" height="50" class="d-block">
                                @else
                                 <img src="{{ Helpers::asset('media/users/default.jpg') }}" alt="" width="50" height="50" class="d-block">
                                @endif
                            </div>
                        @php
                            $User_rating = Helpers::workHistory($workhistory->rating);
                        @endphp
                            <span class="review-star">
                                <?php echo $User_rating  ?> 
                                
                            </span>
                        </div>
                        <div class="history-text">
                            <p class="text-white description"> "{{$workhistory->review}}"
                            </p>
                            <p> <span> <i class="fas fa-user-tie"></i> {{$workhistory->first_name}} {{$workhistory->last_name}} </span>
                                <span> <i class="far fa-calendar-alt"></i>{{ 
                                date('d-m-Y', strtotime($workhistory->created_at))}}
                                </span>
                            </p>
                        </div>
                    </div>
                     @endforeach
                     @endif
                </div>
            </div>
        </div>
    </section>

@if(isset($categories))
    @foreach(@$categories as $key => $category)

        @if(count($category['plans']))
            @php 
                if(isset($user_plans)){
                    $purchase = 'No';
                    foreach($category['plans'] as $plan){
                        if(in_array($plan['id'],$user_plans)){
                            $purchase = 'Yes';
                        }
                    }
                }else{
                    $purchase = 'Yes';
                }
            @endphp
            
                <section class="section-space musicvideo-section music-main">
                    <div class="container">
                        <div class="common-heading agencies-heading text-center">
                            <h2> {{@$category['name']}} </h2>
                        </div>
                        
                        <div class="category-inner">
                            <div class="music-slick">
                                @foreach($category['plans'] as $plan)
                                    @if($price = $plan->getUserPrice(true,$user))
                                        @php
                                            $get_user = auth()->user();
                                        @endphp
                                        <div class="">
                                            <div class="category-box">
                                                <div class="category-icon_box d-flex flex-center flex-column">
                                                    <h3> {{$price}} </h3>
                                                    <p class="text-white shout-t"> {{$plan['name']}} </p>
                                                    <p class="text-14 min-ht"> {!! \Illuminate\Support\Str::limit($plan['description'], 38, $end='...') !!}</p>
                                                    
                                                </div>
                                                <div class="category-link">
                                                    @if($user['id'] != @$get_user['id'] && (  !@$get_user || $get_user->isUser()))
                                                        <a href="{{url('addtocart')}}/{{\Helpers::encrypt($plan->getUserPlan($user))}}">Add To Promotion</b>
                                                    </a>
                                                    @else
                                                      <a href="{{url('addtocart')}}/{{\Helpers::encrypt($plan->getUserPlan($user))}}">Add To Promotion</b>
                                                    </a>
                                                      
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                    @endif
                                @endforeach
                            </div>


                            <button class="prev-arrow" aria-label="Next" type="button"><i
                                    class="fas fa-long-arrow-alt-left"></i></button>
                            <button class="next-arrow" aria-label="Previous" type="button"><i
                                    class="fas fa-long-arrow-alt-right"></i></button>
                        </div>
                    </div>
                </section>
                

        @endif
    @endforeach
@endif

@php
$display_more = false ;
@endphp

@if( !Helpers::isWebview() )
    <section class="other-influencers lastsection-space_b section-space">
        <div class="container">
            <div class="common-heading agencies-heading text-center">
                <h2> Other Influencers You May Like </h2>
            </div>
            <div class="other-influencers_inner">
                @include('influencer.result')
            </div>
        </div>
    </section>
@endif

@endsection
