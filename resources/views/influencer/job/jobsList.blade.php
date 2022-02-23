{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           

<script>
	minPriceInRupees = 0;
    maxPriceInRupees = {{@$maxPrice}};
    currentMinValue = 0;
    currentMaxValue = {{@$maxPrice}};
    maxKMDistance = {{ @$radious }} ;


    platforms = '';
    price_order = '';
    category_checkbox = '';
    race_checkbox = '';
    price_min = 0 ;
    price_max = currentMaxValue ;
    lat = '';
    lng = '';
    radious = maxKMDistance ;
    age = 0 ;
    search = "";
    page = {{@$page?:1}};
    raiting_filter = '' ;
</script>

    <section class="profile-banner browse-banner">

        <div class="minus-banner">
            <div class="banner-inner_logo">
                <img src="images/banner-logo.png" alt="">
            </div>
        </div>
    </section>
<section class="agencies-main section-space bg-blue lastsection-space_b">
   <div class="agencies-outer">
      <div class="container-lg container">
         <div class="agencies-inner">
           <div class="common-heading agencies-heading gradient-border-100 text-center flex-center">
               <h2> {{ \Request::segment(1) == 'my-jobs' ? 'My Jobs' : 'Browse Jobs' }} </h2>
               @if (Auth::check()) 
               <div class="create-jobs">
                  <a href="{{ url('create-job') }}" class=""> 
                     <span class="flex-center"> <i class="fas fa-plus"></i>Create Job </span> 
                  </a>
               </div>
               @endif
            </div>
            <div class="row">
                <div class="col-md-3 col-12 filterWrap">
                    @include('pages.filters')
                </div>
                <!-- --- -->
                <div class="col-md-9 col-12">
                    <div class="browse-search">
                        {{--<form onsubmit="return false">
                            <input type="search" placeholder="Search Influencer" id="search_keyword">
                            <a href="javascript:;"> <i class="fas fa-search"></i> </a>
                        </form>
                    </div>--}}
                    <div class="other-influencers_inner" id="job_result_conainer">
                		<div class="row">
                             @if($jobs->total())
                             @foreach($jobs as $job)
                             <div class="col-12 col-md-12 browse-jobs browse-card">
                                 <div class="agencies-box">
                                    <div class="agencies-detail-wrapper">
                                       <div class="agencies-logo">
                                          <img src="{{ $job->logo() }}" alt="{{$job['title']}}">
                                       </div>
                                       <div class="agencies-detail">
                                          <a href="{{ url('job/'. $job['slug'] ) }}"><h3> {{$job['title']}}</h3></a>
                                          <span class="min-span">Posted :
                                             <span class="text-white">{{ Helpers::dateDifferenceTime($job->id) }}</span>
                                          </span><br/>
                                           <p>{!! \Illuminate\Support\Str::limit(strip_tags($job['description']), 250, $end='...') !!}</p>
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
                                      <div class="view-price_btn"> <a href="{{ url('job/'. $job['slug'] ) }}"> View </a> </div>
                                   </div>
                                </div>
                             </div>
                             @endforeach

                            @if($url = $jobs->nextPageUrl())
                            <div class="white-btn">
                                <a href="javascript:;" nextpage="{{$jobs->currentPage() + 1}}" class="load_next_page"> Load More </a>
                            </div>
                            @endif

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
            </div>
        </div>
    </section>


@endsection