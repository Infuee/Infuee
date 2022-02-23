{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')

@php
$user = auth()->user() ;

@endphp

@if($job->created_by !== auth()->id() )

<section class="agencies-main campaign-title_main section-space bg-blue lastsection-space_b promotional-job">
    <div class="agencies-outer activejob-outer">
        <div class="container-lg">
            <div class="agencies-inner editcampaign-inner d-block">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="title-content_box">
                            <div class="agencies-logo-container d-flex align-item-start justify-content-start">
                                <div class="agencies-logo">
                                    <img src="{{ $job->logo() }}" alt="">
                                </div>
                                <div class="title-detail mt-sm-2">
                                    <div>
                                        <h3>
                                            {{ $job['title'] }}
                                            <span> Duration
                                                <span class="text-white"> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }}</span>
                                            </span>
                                        </h3>
                                        <div class="status-details">
                                            <span class="min-span">Posted :
                                                <span class="text-white">{{ Helpers::dateDifferenceTime($job->id) }}</span>
                                            </span>
                                            <span class="min-span"> Category:
                                                <span class="text-white"> {{$job->category['name'] }} </span> </span>
                                            <span class="min-span"> Platforms:
                                                <span class="text-white active-social_link">
                                                    {!! $job->getPlatformsHTML() !!}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="campaign-right_d status-details">
                                        <!--   <p class=""> Influencers: <span class="text-white"> {{$job->influencers }} </span> </p> -->
                                        @if($job->promo_days)
                                        <p> Duration Days: <span class="text-white"> {{$job->promo_days }} Days </span> </p>
                                        @endif
                                        <p class="me-3"> Price : <span class="text-white"> ${{$job->price }} / Influencer/Post </span> </p>

                                        <span class="min-span">Location :
                                            <span class="text-white">{{$job->location }} ({{$job->radius }} Mile radius)</span>
                                        </span>
                                        <span class="min-span"> Age:
                                            <span class="text-white"> {{ @$job->min_age ? $job->min_age . ' - ' . $job->max_age : "NA" }}</span>
                                        </span>
                                        <span class="min-span"> Race:
                                            @if($race_id && count($race_id))
                                                @foreach($race_id as $race)
                                                    <span class="text-white">{{@$race->title}}
                                                        @if (!$loop->last),@endif
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-white">NA</span>
                                            @endif
                                        </span>
                                        <!-- <span class="min-span"> Rating:
                                            <span class="text-white"> 3<sup>*</sup>+ </span>
                                        </span> -->
                                    </div>
                                    <div>
                                        <span class="description-span">Description :
                                            <span class="text-white">{{ strip_tags($job->description )}}</span>
                                        </span>
                                    </div>
                                    <div class="campaign-discription">
                                        
                                    </div>

                                    <div class="job-details w-100"> 
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <h6> Picture: </h6>
                                                <?php $extension = pathinfo(storage_path($job->image_video()), PATHINFO_EXTENSION);
                                                ?>
                                                @if(@$extension=="mp4")
                                                <div class="agencies-logo browseVideo">
                                                    <video width="320" height="240" controls>
                                                          <source src="{{ $job->image_video() }}" type="video/mp4">
                                                    </video>
                                                </div>
                                                 @else
                                                   <div class="agencies-logo">
                                                      <img src="{{ $job->image_video() }}" alt="">
                                                   </div>
                                                @endif
                                            </div>
                                            <div class="col-sm-7">
                                                
                                                <h6> Caption: </h6>
                                                <p> {{ strip_tags($job->caption )}} </p>
                                                @if( $user && $user->isInfluencer() )
                                                    <div class="apply-btn d-none"> 
                                                        @if($job->isApplied())
                                                            <a href="javascript:;" class="dark-btn"> Update Posts </a>
                                                        @else
                                                            <a href="javascript:;" class="dark-btn"> Apply </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            @if( $user && $user->isInfluencer() )
                            <div class="apply-btn">
                                @if($job->isCompleted())
                                <a href="javascript:void(0)" class="dark-btn"> Job is completed by you </a>
                                @elseif($job->isHired())
                                <a href="{{ url('job/reviews/'. Helpers::encrypt( $job->id ) ) }}" class="dark-btn"> Review Post </a>
                                @elseif($job->isApplied())
                                <a href="{{ url('job/'. $job->slug . '/proposal/submit') }}" class="dark-btn"> Update Posts </a>
                                @else
                                <a href="javascript:void(0)" data-jobid="{{$job->id}}" data-href="{{ url('job/'. $job->slug . '/proposal/submit') }}" class="dark-btn apply"> Apply </a>
                                @endif
                            @endif
                            </div>
                            <div class="apply-btn">
                            @if( $user && $user->isUser() )
                            <a href="javascript:void(0)" data-jobid="{{$job->id}}" data-href="" class="dark-btn userapply"> Apply </a>
                            @endif
                           </div>


                        </div>
                    </div>

                </div>
            </div>

            <hr class="active-border_hr">
            @if( !Helpers::isWebview() )
            <div class="agencies-inner activejob-inner">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="common-heading agencies-heading text-center">
                            <h2> OTHER RELATED JOBS </h2>
                        </div>

                        <div class="col-md-12 col-12">
                            <div class="row">
                                @if($jobs->total())

                                @foreach($jobs as $job)

                                <div class="col-12 col-md-12">
                                    <div class="agencies-box">
                                        <div class="agencies-logo">
                                            <img src="{{ $job->logo() }}" alt="{{$job['title']}}">
                                        </div>
                                        <div class="agencies-detail">
                                            <a href="{{ url('job/'. $job['slug'] ) }}">
                                                <h3> {{$job['title']}}</h3>
                                            </a>
                                            <span class="min-span">Posted :
                                                <span class="text-white">{{ Helpers::dateDifferenceTime($job->id) }}</span>
                                            </span>
                                            <p class=""> {!! $job['description'] !!}</p>
                                            <span class="min-span text-white">
                                                {!! $job->getPlatformsHTML() !!} </span>
                                            <span class="min-span">
                                                <!-- <i class="fas fa-clock"></i>  -->
                                                <img src="images/clock-icon.png" alt="clock" width="18" height="18">
                                                <span> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }}</span>
                                            </span>
                                        </div>
                                        <div class="price-view">
                                            <p> ${{$job['price']}} </p>
                                            <div class="view-price_btn"> <a href="{{ url('job/'. $job['slug'] ) }}"> View </a> </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                                @else
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <img src="media/images/No_result.png">
                                        <h3 class="no_order text-white">No Jobs are posted yet</h3>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @if($url = $jobs->nextPageUrl())
                        <div class="white-btn">
                            <a href="{{$url}}" class=""> Load More </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @endif
        </div>
    </div>
</section>

@else

<section class="agencies-main campaign-title_main section-space bg-blue lastsection-space_b">
    <div class="agencies-outer activejob-outer">
        <div class="container-lg">
            <div class="agencies-inner editcampaign-inner">
                <div class="row">
                    <div class="col-12 col-md-12">
                        <div class="title-content_box d-flex flex-row justify-content-between align-items-start pb-0 mb-0">
                            <div class="d-flex flex-row justify-content-start align-items-start agencies-logo_container">
                                <div class="agencies-logo">
                                    <img src="{{ $job->logo() }}" alt="">
                                </div>
                                <div class="title-detail d-flex flex-column justify-content-start align-items-start">
                                    <div>
                                        @php
                                        $rating = Helpers::avgRateingJobs($job['id']);
                                        $average = $rating['average'];
                                        @endphp
                                        <h3> {{ $job['title'] }}

                                            <span> Duration <span class="text-white"> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }}</span> </span>
                                        </h3>
                                        <div class="status-details">
                                            <span class="min-span">Posted :
                                                <span class="text-white">{{ Helpers::dateDifferenceTime($job->id) }}</span>
                                            </span>
                                            <span class="min-span"> Category:
                                                <span class="text-white"> {{$job->category['name'] }} </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="campaign-discription">
                                        {!! $job->description !!}
                                    </div>
                                </div>
                            </div>
                            <div class="edit-detail">
                                <a href="{{ url('/job/'. $job->slug .'/edit') }}"> <i class="far fa-edit" aria-hidden="true"></i> Edit Job Detail </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="job-platforms">
                <h3> Platforms </h3>
                <div class="job-social_platform">
                    {!! $job->getSocialMediaImage() !!}
                </div>
                <div class="job-detail_b">
                    @if(@$job->campaign_id)
                    <span>
                        <img src="{{ Helpers::asset('images/influencer-icon.png') }}" alt=""> Influencers:
                        <span class="text-white"> {{$job->influencers }}</span>
                    </span>
                    @endif
                    @if($job->promo_days)
                    <span>
                        <img src="{{ Helpers::asset('images/duration-icon.png') }}" alt=""> Duration Day:
                        <span class="text-white"> {{$job->promo_days }}</span>
                    </span>
                    @endif
                    <span>
                        <img src="{{ Helpers::asset('images/pricetag-icon.png') }}" alt=""> Price:
                        <span class="text-white"> ${{$job->price }}/ Day </span>
                    </span>
                    <span>
                        <img src="{{ Helpers::asset('images/pricetag-icon.png') }}" alt="">
                        <a href="{{ url('/job/'. $job->slug .'/proposals') }}"><span class="text-white"> Post Influencer Posts </span> </a>
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>



@endif

@endsection