@if(count($influencers))
    <div class="row">
		@foreach($influencers as $influencer)
		<div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-4">
            <div class="influencer-outerbox">
                <div class="inner-box_pf flex-center">
                    <div class="profile-img_box">
                        <span class="price-span"> ${{@$influencer->getPrice($influencer['id'])}} </span>
                        <div class="center-img">
                            <a href="{{url('influencer')}}/{{$influencer['username']?$influencer['username']:\Helpers::encrypt($influencer['id'])}}/profile">
                            @if(is_file(public_path("/media/users").'/'.@$influencer['image']))
                                <img src="{{asset('media/users/'.$influencer['image'])}}">
                            @else
                                <img src="media/users/blank.png">
                            @endif</a>
                        </div>
                        <h4> {{@$influencer['first_name']}} </h4>
                        <p> {{@$influencer->getCategory()}}</p>

                    </div>
                    
                </div>
                <div class="social-bottom">               
                    {!! $influencer->getSocialPlatformsHTML() !!}            
                </div>
            </div>
        </div>
		@endforeach
	</div>
@else
<div class="row">
    <div class="col-md-12 col-sm-12 text-center noResult-img">
        <img src="media/images/No_result.png">
        <h3 class="no_order">No Result Found</h3>
    </div>
</div>
@endif

@if( $url = $influencers->nextPageUrl() && !isset($display_more) )

<div class="white-btn d-flex align-items-center">
    @if( $influencers->previousPageUrl()  )
        {{--<a href="javascript:;" nextpage="{{$influencers->currentPage() - 1}}" class="load_next_page"> Back </a>--}}
    @endif    
    <a href="javascript:;" nextpage="{{$influencers->currentPage() + 1}}" class="load_next_page"> Load More </a>
</div>
@endif