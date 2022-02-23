{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 


     <section class="proposal-main lastsection-space_b bg-blue section-space">
        <div class="proposal-outer">
            <div class="container-md">
                <div class="proposal-inner">
                    <div class="proposal-innerBox">
                        <div class="title-detail mt-md-2 mt-4">
                            <div>
                                <h3> {{ $job['title'] }}
                                    <span> Duration: <span class="text-white"> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }}</span> </span>
                                </h3>
                                <div class="status-details">
                                    <span class="min-span"> Created at :<span class="text-white"> {{ date('d M Y', strtotime($job->created_at)) }}</span></span>
                                </div>
                            </div>
                            <!-- <div class="title-content_v">
                                <h3> {{ $job['title'] }}<span> Duration: <span class="text-white"> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }}</span> </span>
                                <span> Created at :<span class="text-white"> {{ date('d M Y', strtotime($job->created_at)) }}</span></span>

                                </h3>
                                <p class="view-description">{!! $job->description !!}</p>
                            </div> -->
                            <div class="campaign-right_d">
                                @if($job->campaign_id != null)
                                <p class="min-span"> Influencers: <span class="text-white"> {{$job->influencers }} </span> </p>
                                {{--<p class="min-span">Total Hired Influencers: <span class="text-white"> {{ $proposalCount }} </span> </p>--}}
                                @endif

                                @if($job->promo_days)<p class=""> Duration Days: <span class="text-white"> {{$job->promo_days }} Days </span> </p>@endif
                                <p class="min-span"> Price: <span class="text-white"> ${{$job->price }} / Days </span> </p>
                            </div>
                        </div>
                        <div class="campaign-discription">{!! $job->description !!}</div>
                    </div>

                    <hr class="view-propsal_hr">

                    <div class="proposal-content">
                        @if(count($proposals))
                        @foreach($proposals as $proposal)
                        <div class="proposal-box">
                            <div class="proposal-profile">
                                <div class="profile-detail">
                                    <div class="profile-content">
                                        <div class="d-flex"> 
                                            <div class="profile-img">
                                                @if(is_file(public_path("/media/users").'/'.@$proposal['user']['image']))
                                                    <img src="{{ Helpers::asset('media/users/'.$proposal['user']['image'])}}">
                                                @else
                                                    <img src="{{ Helpers::asset('media/users/blank.png') }}">
                                                @endif
                                            </div>
                                        
                                            <div class="profile-inline">
                                            <div class="d-flex align-items-center mb-2">


                                                @if($image = @$proposal['user']->getCountryFlag())
                                                    <img class="flag-img" src="{{ Helpers::asset('media/country_flag/'.$image) }}"/>
                                                @else
                                                    <i class="fa fa-flag"></i>  
                                                @endif
                                                
                                                <h4> {{@$proposal['user']['first_name'].' '.@$proposal['user']['last_name']}} <img src="{{ Helpers::asset('images/verify-icon.png') }}" alt="verify"> </h4>
                                            </div>
                                            @php
                                                $rating = Helpers::avgRateing($proposal['influencer_id']);
                                                $rating['totalRating'];
                                               
                                            @endphp
                                            <div>
                                                <span class="review-star"> 
                                                   @include('influencer.job.common',$rating) 
                                                    <span> {{$rating['average']}}* ({{$rating['total_users']}} User) 
                                                </span>
                                                    
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hire-outer desktop-hire_btn">


                                    </div>

                                    
                                    
                                    <div class="propsal-description">
                                      
                                        
                                        <a class="view-more_link" href="{{ url('job/'. $job['slug'] . '/proposal/' . Helpers::encrypt($proposal['id']) ) }}"> Check Post  <i class="fas fa-chevron-right"></i></a>
                                    </div>

                                </div>
                              </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                          <h4 class="text-center text-white">No Proposals</h4>
                        @endif
                        @if( $url = $proposals->nextPageUrl() )
                            <div class="white-btn">
                                <a href="{{ $url }}" class=""> Load More </a>
                            </div>
                        @endif

                   
                </div>
            </div>
        </div>
    </section>

@endsection


