{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 

 
    <section class="proposal-main lastsection-space_b bg-blue section-space">
        <div class="proposal-outer">
            <div class="container-md">
                <div class="proposal-inner">
                    <div class="proposal-content proposal-profile">
                        
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
                                                    $rating = Helpers::avgRateing($proposal->user['id']);
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
                                        <div class="hire-outer">
                                            <p class="price-day_p"> ${{ number_format($job['price'], 2) }}/Per Influencer/Post </p>
                                            <a href="{{ url('message/'. Helpers::encrypt( $proposal['job_id'] ) ) }}" class="convo-link">
                                                <img src="images/conversation1.png" alt="Conversation" class="conversation-btn"> </a>
                                        </div>
                                    </div>

                                    {{--<div class="propsal-description">
                                        <h5 class="letter_h"> Cover Letter</h5>
                                        {!! @$proposal['cover_latter'] !!}
                                    </div>--}}

                                </div>
                            </div>
                        </div>
                        <div class="attach-files_outer">
                           {{-- @php
                            $attachments = json_decode($proposal->getAttachments());
                            @endphp
                            
                            @if(count($attachments))
                                <h5 class="letter_h"> Attached Files </h5>
                                <div class="attach-file_inner">
                                    

                                    @foreach($attachments as $attachment)

                                    <div class="choose-file d-flex align-items-center justify-content-center">
                                        <a href="{{$attachment->original}}" target="_blank">
                                            <img src="{{ $attachment->display_url }}">
                                        </a>
                                    </div>

                                    @endforeach

                                </div>
                            @endif--}}
                            @if(count($posturl))
                                <h5 class="letter_h"> Post Url </h5>
                                <div class="attach-url">
                                    @foreach($posturl as $url)
                                    <div class="">
                                        <a class="text-white" href="{{$url->url}}" target="_blank">
                                            {!! $url->getPlatformsHTML($url->platform_id) !!} {{ $url->url }}
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                           

                            <div class="prop_btn_b fundsbtn-outer">


                                @if( $proposal->job->isFeedbackGiven( @$proposal['influencer_id'] ) )
                                    <div class="cancel-btn">
                                        
                                        <a href="javascript:void(0)" class="btn btn-light"> Approved And Funded </a>
                                    </div>
                                    @elseif( $proposal->job->isHired( @$proposal['influencer_id'] ) )
                                    <div class="cancel-btn text-sm-left text-center">
                                        
                                        <a href="javascript:void(0)" class="btn btn-light me-sm-3"> Approved And Funded </a>
                                        @if(empty(@$reviews))
                                        <a href="{{ url('job/reviews/'. Helpers::encrypt( $job['id'] ) ) }}" class="dark-btn mt-3 mt-sm-0"> Feedback  Influencer </a>
                                        @endif
                                    </div>
                                    @elseif(Helpers::checkJobStatus($proposal['job_id']) == '4') 
                                    <div class="cancel-btn me-4">
                                        <a href="javascript:;" class="btn btn-dark"> Cancel </a>
                                    </div>
                                    <div>
                                        <a href="{{ url('job/reviews/'. Helpers::encrypt( $proposal['job_id'] ) ) }}" class="convo-link btn btn-light">Jobs Reviews</a>
                                    </div>
                                    @else
                                    <div class="cancel-btn me-4">
                                        <a href="{{ url('job/'.$job['slug'].'/'.'proposals') }}" class="btn btn-dark"> Cancel </a>
                                    </div>
                                    <div>

                                        <a href="javascript:;" class="hirebtn1 btn btn-light funds-btn" data-name="Want to approve these Posts and release funds?" data-wallet-amount="{{ @$wallet->amount }}"  id="hirejobs1" data-url="{{ @$proposal->job->slug }}" data-jobid="{{$proposal['job_id']}}" data-jobhiretatus="1" data-jobcost="{{$job->price }}" data-cost="{{ Helpers::checkWalletforHire() }}" data-influencers="{{$proposal['user']['id']}}"> Approve and release funds</a>

                                    </div>
                                    @endif
                                    

                            </div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
        </div>
    </section>


@endsection