{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 


    <section class="campaign-main section-space bg-blue lastsection-space_b">
        <div class="campaign-outer">
            <div class="container">
                <div class="campaign-head_outer">
                    <div class="common-heading campaign-heading gradient-border-100">
                        <h2> Campaign </h2>
                    </div>
                    @if (Auth::check())
                    <div class="create-campaign text-end">
                        <a href="{{ url('create/campaign') }}" class=""> <span class="flex-center"> <i class="fas fa-plus"></i> </span>
                            Create campaign </a>
                    </div>
                    @endif
                </div>
                <div class="campaign-inner">

                    <div class="row">
                    
                    @if($campaigns->total())
                      @foreach($campaigns as $campaign)

                        @if(count($campaign['jobsCount']) || auth()->id() == @$campaign['created_by'])
                      <div class="col-sm-12 col-md-6 col-lg-4">
                            <div class="company-box">
                                <div class="company-logo">
                                    <img src="{{ $campaign->logo() }}" alt="">
                                </div>
                                <div class="company-detail">
                                    <h3> <a href="{{ url('campaign/'. @$campaign['slug'] ) }}"> {{ $campaign['title'] }} </a></h3>
                                    @if(count($campaign['jobsCount']) > 0)
                                    <p class="status"> Active Jobs: <span class="text-white"> {{ count($campaign['jobsCount']) }} </span> </p>
                                    @else
                                      <p class="status"> Active Jobs: <span class="text-white">No Active Job </span> </p>
                                    @endif
                                    <p class="post-time">Posted :<span class="text-white">{{ $campaign['created_at'] ? $campaign['created_at']->diffForHumans() : ' '}}</span></p>
                                    <a href="{{ url('campaign/'. @$campaign['slug'] ) }}" class="view-more_link"> View More <i
                                            class="fas fa-chevron-right"></i> </a>
                                </div>
                            </div>
                        </div>
                        @endif
                      @endforeach
                    </div>

                    {!! $campaigns->render() !!}

                  @if(0 && $url = $campaigns->nextPageUrl())
                      <div class="white-btn">
                          <a href="{{$url}}" class=""> Load More </a>
                      </div>
                  @endif

                    @else
                       
                    <div class="row">
                        <div class="col-md-12 col-sm-12 text-center">
                            <img src="media/images/No_result.png">
                            <h3 class="no_order text-white">You do not have created any campaign yet.</h3>
                        </div>
                    </div>

                    @endif


                </div>
            </div>
        </div>
    </section>



@endsection