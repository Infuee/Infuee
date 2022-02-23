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
    page = 1;
    raiting_filter = '' ;
</script>

    <section class="profile-banner browse-banner">

        <div class="minus-banner">
            <div class="banner-inner_logo">
                <img src="images/banner-logo.png" alt="">
            </div>
        </div>
    </section>

    <section class="other-influencers lastsection-space_b section-space">
        <div class="container-lg">

            <div class="row">
                <div class="col-md-3 col-12 filterWrap">
                    @include('pages.filters')
                </div>
                <!-- --- -->
                <div class="col-md-9 col-12">
                    <div class="browse-search">
                        <form onsubmit="return false">
                            <input type="search" placeholder="Search Influencer" id="search_keyword">
                            <a href="javascript:;"> <i class="fas fa-search"></i> </a>
                        </form>
                    </div>
                    <div class="other-influencers_inner" id="influencer_result_conainer">
                		@include('influencer.result')                    
                	</div>
                </div>
            </div>
        </div>
    </section>


@endsection