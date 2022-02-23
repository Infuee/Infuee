@if(1 || $page > 1)
@else
<section class="agencies-main section-space bg-blue lastsection-space_b">
   <div class="agencies-outer">
      <div class="container-lg container">
         <div class="agencies-inner">
	<div class="row">
                       <div class="col-md-12 col-12">
                          <div class="row">
@endif                           
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
                                @if($page > 1)
                                <div class="row">
                                   <div class="col-md-12 col-sm-12 text-center">
                                      <img src="media/images/No_result.png">
                                      <h3 class="no_order text-white">No Jobs are filtered.</h3>
                                   </div>
                                </div>
                                @endif
                             @endif

@if(1 || $page > 1)
@else
                          </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif