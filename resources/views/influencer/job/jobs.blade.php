{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')
<section class="agencies-main my-jobs_wrapper section-space bg-blue lastsection-space_b">
   <div class="agencies-outer">
      <div class="container-lg container">
         <div class="agencies-inner">
            <div class="common-heading agencies-heading gradient-border-100 text-center flex-center">
               <h2> {{ \Request::segment(1) == 'my-jobs' ? 'My Jobs' : 'Browse Jobs' }} </h2>
               @if (Auth::check())
               <div class="create-jobs">
                  <a href="{{ url('create-job') }}">
                     <span class="flex-center"> <i class="fas fa-plus"></i>Create Job </span>
                  </a>
               </div>
               @endif
            </div>

            @if(Request::segment(1) == 'my-jobs')
            <tab-container class="jobstabs">
               <!-- TAB CONTROLS -->
               <input type="radio" id="tabToggle01" name="tabs" value="1" checked />
               <label for="tabToggle01" checked="checked">My Jobs</label>
               <input type="radio" id="tabToggle02" name="tabs" value="2" />
               <label for="tabToggle02">Active Jobs</label>
               <input type="radio" id="tabToggle03" name="tabs" value="3" />
               <label for="tabToggle03">Completed Jobs</label>
               <!-----------My job -------------------->
               <tab-content>
                  <div class="row">
                     <div class="col-md-12 col-12">
                        <div class="browse-jobs d-flex flex-column">
                           @if($jobs->total())
                           @foreach($jobs as $job)
                           <div class="agencies-box">
                              <div class="agencies-detail-wrapper">
                                 <div class="agencies-logo">
                                    <img src="{{ $job->logo() }}" alt="{{$job['title']}}">
                                 </div>
                                 <div class="agencies-detail">
                                    <a href="{{ url('job/'. $job['slug'] ) }}">
                                       <h3> {{$job['title']}}</h3>
                                    </a>
                                    <span class="min-span">Posted :
                                       <span class="text-white"> {{ Helpers::dateDifferenceTime($job->id) }}</span>
                                    </span>
                                    {!! $job['description'] !!}
                                    <div class="followers-list">
                                       <span class="min-span text-white"> {!! $job->getPlatformsHTML() !!} </span>
                                       <span class="min-span">
                                          <!-- <i class="fas fa-clock"></i>  -->
                                          <img src="images/clock-icon.png" alt="clock" width="18" height="18">
                                          <span> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }}</span>
                                       </span>
                                       <!-- <br> -->
                                       <!-- <span class="text-white">Posted : {{ Helpers::dateDifferenceTime($job->id) }}</span> -->
                                    </div>
                                 </div>
                              </div>
                              <div class="price-view">
                                 <p> ${{$job['price']}} </p>
                                 <div class="view-price_btn">
                                    <a href="{{ url('job/'. $job['slug'] ) }}"> View </a>
                                 </div>
                              </div>
                           </div>
                           @endforeach

                           {!! $jobs->render() !!}


                           @else
                           <div class="text-center">
                              <img class="img-fluid" src="media/images/No_result.png">
                              <h3 class="no_order text-white">No Jobs are posted yet</h3>
                           </div>
                           @endif
                        </div>
                     </div>
                  </div>
               </tab-content>
               <!-----------End My job -------------------->

               <!-----------My job -------------------->
               <tab-content>
                  <div class="row">
                     <div class="col-md-12 col-12">
                        <div class="browse-jobs d-flex flex-column">
                           @if($activeJobs->total())
                           @foreach($activeJobs as $job)
                           <!-- <div class="col-12 col-md-12 browse-jobs"> -->
                           <div class="agencies-box">
                              <div class="agencies-detail-wrapper">
                                 <div class="agencies-logo">
                                    <img src="{{ $job->logo() }}" alt="{{$job['title']}}">
                                 </div>
                                 <div class="agencies-detail">
                                    <a href="{{ url('job/'. $job['slug'] ) }}">
                                       <h3> {{$job['title']}}</h3>
                                    </a>
                                    
                                    <br />
                                    <span class="min-span">Posted :
                                       <span class="text-white"> {{ Helpers::dateDifferenceTime($job->id) }}</span>
                                    </span>
                                    {!! $job['description'] !!}
                                    <div class="followers-list">
                                       <span class="min-span text-white"> {!! $job->getPlatformsHTML() !!} </span>
                                       <span class="min-span">
                                          <i class="fas fa-clock"></i>
                                          <span class="text-white"> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }}</span>
                                       </span>
                                       <!-- <br> -->
                                       <!-- <span class="text-white">Posted : {{ Helpers::dateDifferenceTime($job->id) }}</span> -->
                                    </div>
                                 </div>
                              </div>
                              <div class="price-view">
                                 <p> ${{$job['price']}} </p>
                                 <div class="view-price_btn">
                                    <a href="{{ url('job/'. $job['slug'] ) }}"> View </a>
                                 </div>
                              </div>
                           </div>
                           <!-- </div> -->
                           @endforeach

                           {!! $activeJobs->render() !!}

                           @else
                           <div class="text-center">
                              <img src="media/images/No_result.png">
                              <h3 class="no_order text-white">You do not have hired any influencer yet.</h3>
                           </div>
                           @endif
                        </div>
                     </div>
                  </div>
               </tab-content>
               <!-----------End My job -------------------->

               <!-----------My Active job Completed-------------------->
               <tab-content>
                  <div class="row">
                     <div class="col-md-12 col-12">
                        <div class="browse-jobs d-flex flex-column">
                           @if($completedjobs->total())
                           @foreach($completedjobs as $job)

                           @php
                           $rating = Helpers::avgRateingJobs($job['id']);
                           $average = $rating['average'];
                           @endphp

                           <div class="agencies-box">
                              <div class="agencies-detail-wrapper">
                                 <div class="agencies-logo">
                                    <img src="{{ $job->logo() }}" alt="{{$job['title']}}">
                                 </div>
                                 <div class="agencies-detail aaaaa">
                                    <a href="{{ url('job/'. $job['slug'] ) }}">
                                       <h3> {{$job['title']}}</h3>
                                    </a>
                                    <span class="min-span">Posted :
                                       <span class="text-white"> {{ Helpers::dateDifferenceTime($job->id) }}</span>
                                    </span>
                                    <div class="jobreating-star">
                                       <span class="review-star">
                                          @include('influencer.job.common',$rating)
                                          <span> {{$rating['average']}}* ({{$rating['total_users']}} User)</span>
                                       </span>
                                    </div>
                                    {!! $job['description'] !!}
                                    <div class="followers-list">
                                       <span class="min-span text-white">{!! $job->getPlatformsHTML() !!} </span>
                                       <span class="min-span">
                                          <!-- <i class="fas fa-clock"></i>  -->
                                          <img src="images/clock-icon.png" alt="clock" width="18" height="18">
                                          <span> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }}</span>
                                       </span>
                                       <!-- <br> -->
                                       <!-- <span class="text-white">Posted : {{ Helpers::dateDifferenceTime($job->id) }}</span> -->
                                    </div>
                                 </div>
                              </div>
                              <div class="price-view">
                                 <p> ${{$job['price']}} </p>
                                 <div class="view-price_btn"> <a href="{{ url('job/'. $job['slug'] ) }}"> View </a> </div>
                              </div>
                           </div>
                           @endforeach

                           {!! $completedjobs->render() !!}

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
                  </div>
               </tab-content>
               <!-----------End My Active job Completed-------------------->

            </tab-container>

            @else
            <div class="row">
               <div class="col-md-12 col-12">
                  <div class="browse-jobs d-flex flex-column job-outer">
                     @if($jobs->total())
                     @foreach($jobs as $job)
                     <div class="agencies-box">
                        <div class="agencies-detail-wrapper">
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

                              <span class="description-span">Description :
                                 <span class="text-white">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ipsa repellendus sit sapiente deserunt mollitia quaerat fugit doloribus sunt est unde.</span>
                              </span>


                              {!! \Illuminate\Support\Str::limit($job['description'], 250, $end='...') !!}


                              <div class="status-details mt-4">
                                 <span class="min-span">Location :
                                    <span class="text-white">Huntsville AL (10 Mile radius)</span>
                                 </span>
                                 <span class="min-span"> Age:
                                    <span class="text-white"> 18-50</span>
                                 </span>
                                 <span class="min-span"> Race:
                                    <span class="text-white">N/A</span>
                                 </span>
                                 <span class="min-span"> Rating:
                                    <span class="text-white"> 3<sup>*</sup>+ </span>
                                 </span>
                                 <span class="min-span"> Platforms:
                                    <span class="text-white active-social_link">
                                       <img src="/images/job-insta-icon.png?v=1625198579" alt="">Min. 120k Followers<img src="/images/job-fb-icon.png?v=1625198579" alt="">Min. 150k Followers
                                    </span>
                                 </span>
                              </div>

                              <div class="followers-list d-none">
                                 <span class="min-span text-white"> {!! $job->getPlatformsHTML() !!} </span>
                                 <span class="min-span">
                                    <!-- <i class="fas fa-clock"></i>  -->
                                    <img src="images/clock-icon.png" alt="clock" width="18" height="18">
                                    <span> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }}</span>
                                 </span>
                                 <!-- <br> -->
                                 <!-- <span class="text-white">Posted : {{ Helpers::dateDifferenceTime($job->id) }}</span> -->
                              </div>
                           </div>
                        </div>
                        <div class="price-view">
                           <p> ${{$job['price']}} </p>
                           <div class="view-price_btn"> <a href="{{ url('job/'. $job['slug'] ) }}"> View </a> </div>
                        </div>
                     </div>
                     @endforeach

                     {!! $completedjobs->render() !!}

                     @else
                     <div class="text-center">
                        <img src="media/images/No_result.png">
                        <h3 class="no_order text-white">No Jobs are posted yet</h3>
                     </div>
                     @endif
                  </div>
               </div>
            </div>
            @endif

            @if(0&&$url = $jobs->nextPageUrl())
            <div class="white-btn">
               <a href="{{$url}}" class=""> Load More </a>
            </div>
            @endif
         </div>
      </div>
   </div>
</section>
@endsection