

{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')
<section class="agencies-main section-space bg-blue lastsection-space_b">
    <div class="agencies-outer">
        <div class="container-lg">
            <div class="agencies-inner">

                <div class="common-heading agencies-heading gradient-border-100 text-center flex-center">
                    <h2> {{ \Request::segment(1) == 'my-active-jobs' ? 'Active Jobs' : 'Active Jobs' }} </h2>
                </div>

                <div class="row">

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
                                                    <h3> {{$job['title']}}</h3>
                                                    
                                                    <p class=""> {!! $job['description'] !!}</p>
                                                    <span class="min-span text-white">{!! $job->getPlatformsHTML() !!} </span>
                                                    <span class="min-span"> 
                                                        <!-- <i class="fas fa-clock"></i>  -->
                                                        <img src="images/clock-icon.png" alt="clock" width="18" height="18">
                                                        <span> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }}</span>
                                                    </span>
                                                </div>
                                                <div class="price-view">
                                                    <p> ${{$job->price}} </p>  

                                                    @php
                                                        if($job['status'] == 4){
                                                            $checked = 'checked';
                                                    @endphp
                                                    <div class="form-group-checkbox">
                                                        <!---input type="checkbox" name="" class="job_mark_done" data-id="{{$job['id']}}" id="jobdonemarked" {{$checked}} data-jobstatus="3">
                                                        <label for="mark{{$job['id']}}">Job Done </label--->
                                                        <h5>Job Done</h5>
                                                    </div>
                                                    @if(Helpers::checkUserReviews($job['id']) == 0)
                                                    <!---a href="{{ url('job/reviews/'. Helpers::encrypt( $job['id'] )  ) }}" class="convo-link">Jobs Reviews</a--->
                                                    @endif
                                                    @php
                                                    }else{
                                                    $checked = '';
                                                    @endphp
                                                    <!---div class="form-group-checkbox">
                                                        <input type="checkbox" name="" data-job="{{$job['title']}}" class="job_mark_done" data-id="{{$job['id']}}" id="jobdonemarked" {{$checked}} data-jobstatus="4">
                                                        <label for="mark{{$job['id']}}">Marked For Job Done</label>

                                                    </div---->

                                                    {{--<form class="form-horizontal" method="POST" action="{{ url('job/jobcompletes/'. Helpers::encrypt( $job['id'] )  ) }}" enctype="multipart/form-data">
                                                                  {{ csrf_field() }}
                                                            <input name="jobname" id="jobnames" type="hidden" value="{{ $job['title'] }}">

                                                            <div class="view-price_btn"> 
                                                                <a class="js-submit-confirm">
                                                                    <i class="fas fa-thumbs-up"></i>
                                                                    Job Done
                                                                </a>
                                                            </div>
                                                    </form> --}}




                                                    @php           
                                                        }
                                                    @endphp
                                                    

                                                    
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                
                                @else


                                <div class="row">
                                    <div class="col-md-12 col-sm-12 text-center">
                                        <img src="media/images/No_result.png">
                                        <h3 class="no_order text-white">You do not have any active job</h3>
                                    </div>
                                </div>


                                @endif
                        </div>
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
</section>

@endsection
