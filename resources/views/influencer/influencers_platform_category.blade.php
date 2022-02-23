{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 

    <section class="category-main section-space bg-blue lastsection-space_b">
        <div class="category-outer">
            <div class="container">
                <div class="common-heading text-center">
                    @if(\Request::segment(1) == "influencer")
                    <h2> Select Category for {{ @$platformName['name'] }} Influencers </h2>
                    @else
                    <h2> Select {{@$categoryName->name}} Category for {{ @$platformName['name'] }} Influencers </h2>
                    @endif
                </div>
                <div class="browse-search">
                    @if(count($platform_catogery))
	                <form onsubmit="return false">
	                    <input type="text" id="search-input" onkeyup="searchup()" onkeydown="searchdown()" placeholder="Search Example:User Name" data-catId="{{@$id ? @$id : ''}}">
	                    <a href="javascript:;"> <i class="fas fa-search"></i> </a>
	                </form>
                    @endif
                </div>
                <div class="category-inner container">
                    <div class="row">
                        @if(count($platform_catogery))
                        @foreach($platform_catogery as $platform_cat)
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4" id="search-results">
                            <div class="category-box">
                                <a href="{{url('influencer')}}/{{@$platform_cat->username?@$platform_cat->username:\Helpers::encrypt(@$platform_cat->id)}}/profile">
                                    <div class="category-icon_box d-flex flex-center flex-column"> 
                                        <div class="profile-img_box center-img">
                                            <span class="price-span">${{\Helpers::getUserPlan($platform_cat->id)}} </span>
                                            @if(@$platform_cat->image)
                                            <img src="{{ Helpers::asset('media/users/'.@$platform_cat->image) }}" alt="">
                                            @else
                                            <img src="media/users/blank.png" alt="">
                                            @endif
                                        </div>
                                        <h4>{{@$platform_cat->first_name}}</h4>
                                        <p> {{@$categoryName->name}}</p>
                                        @php
                                            $rating = Helpers::avgRateing($platform_cat->id);
                                            $rating['totalRating'];
                                        @endphp
                                        <span class="review-star">
                                            @include('influencer.job.common',$rating) 
                                        </span>  
                                    </div>
                                </a>
                                <div class="category-link">
                                    <a href="{{url('influencer')}}/{{@$platform_cat->username?@$platform_cat->username:@$platform_cat->id}}/profile"> View <i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @else
						<div class="col-md-12 col-sm-12 text-center">
							<img src="media/images/No_result.png" class="noResult">
							<h3 class="no_order">No Result Found</h3>
						</div>
                        @endif

                        @if( $url = $platform_catogery->nextPageUrl() && !isset($display_more) )
						<div class="white-btn d-flex align-items-center">
						    @if( $platform_catogery->previousPageUrl()  )
						    {{--<a href="javascript:;" nextpage="{{$platform_catogery->currentPage() - 1}}" class="load_next_page"> Back </a>--}}
						    @endif    
						    <a href="javascript:;" nextpage="{{$platform_catogery->currentPage() + 1}}" class="load_next_page"> Load More </a>
						</div>
						@endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection