<div class="filterMenu filterMenu--mobile">
                        <div class="filterMenuInner">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <h4>Filters</h4>

                    </div>
                    <div class="filters_outer" id="filter-sidebar">
                        <div class="filters-inner">
                            <div class="filters-heading">
                                <h3> Filter By </h3>
                            </div>
                            <div class="filters-content">
                                @if(\Request::segment(1) == 'influencers')
                                <h4> Influencers </h4>
                                 @else 
                                <h4> Social Platforms </h4>
                                @endif
                                <div class="filters-list_outer">
                                    <ul class="filter-menu ps-0">
                                        @if(@$platforms)
                                        @foreach($platforms as $platform)
											<li class="sub-filter">
	                                            <input class="platform_checkbox" type="checkbox" id="{{ $platform['name'] }}" value="{{ $platform['slug'] }}"
                                                    
                                                >
	                                            <label for="{{ $platform['name'] }}">{{ $platform['name'] }}</label>
	                                        </li>                                        
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <hr class="filter-hr">

                            <div class="filters-content">
                                <h4> Price </h4>
                                <div class="filters-list_outer">
                                    <ul class="filter-menu ps-0">
                                        <li class="sub-filter">
                                            <input name="price_order" class="price_order" value="lowheigh" type="radio" id="low-to-high" checked>
                                            <label for="low-to-high">Lower to Higher</label>
                                        </li>
                                        <li class="sub-filter">
                                            <input name="price_order" class="price_order" value="heighlow" type="radio" id="high-to-low">
                                            <label for="high-to-low"> Higher to Lower</label>
                                        </li>
                                    </ul>
                                </div>
                            </div> 

                            <hr class="filter-hr">

                            <div class="filters-content">
                                <h4> Ratings </h4>
                                <div class="filters-list_outer">
                                    <div class="star-rating">
                                            <input class="raiting_filter" type="radio" id="5-stars" name="starrating" value="5" />
                                            <label for="5-stars" class="star">&#9733;</label>
                                            <input class="raiting_filter" type="radio" id="4-stars" name="starrating" value="4" />
                                            <label for="4-stars" class="star">&#9733;</label>
                                            <input class="raiting_filter" type="radio" id="3-stars" name="starrating" value="3" />
                                            <label for="3-stars" class="star">&#9733;</label>
                                            <input class="raiting_filter" type="radio" id="2-stars" name="starrating" value="2" />
                                            <label for="2-stars" class="star">&#9733;</label>
                                            <input class="raiting_filter" type="radio" id="1-star" name="starrating" value="1" />
                                            <label for="1-star" class="star">&#9733;</label>
                                            </div>
                                </div>
                            </div>

                            <hr class="filter-hr">

                            <div class="filters-content">
                                <h4> Category </h4>
                                <div class="filters-list_outer">
                                    <ul class="filter-menu ps-0">
                                        @if(@$categories)
                                    	@foreach($categories as $key => $category)
											<li class="sub-filter">
	                                            <input class="category_checkbox" type="checkbox" id="{{ $category }}" value="{{ $key }}">
	                                            <label for="{{ $category }}">{{ $category }}</label>
	                                        </li>                                        
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <hr class="filter-hr">

                            <div class="filters-content">
                                <h4> Race </h4>
                                <div class="filters-list_outer">
                                    <ul class="filter-menu ps-0">
                                        @if(@$race)
                                        @foreach($race as $key => $row)
                                            <li class="sub-filter">
                                                <input class="race_checkbox" type="checkbox" id="{{ $row }}" value="{{ $key }}">
                                                <label for="{{ $row }}">{{ $row }}</label>
                                            </li>                                        
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <hr class="filter-hr">

                            <div class="filters-content">
                                <h4> Price Range </h4>
                                <div class="filters-list_outer">
                                    <div data-role="rangeslider">
                                        <label for="price-min">Price:  $<span id="min-price">0</span> - $<span id="max-price">{{@$maxPrice}}</span> </label>
                                        <div id="price-range"></div>
                                    </div>
                                </div>
                            </div>

                            <hr class="filter-hr">

                            <div class="filters-content">
                                <h4> Age </h4>
                                <div class="filters-list_outer">
                                    <span class="select-arrow_span"> 
                                     <?php if(\Request::segment(1) == 'influencers'){
                                     ?>    
                                    <select id="influencer_age">
                                        <option value="0"> Age </option>
	                                    @foreach( \App\User::ageFilter() as $key => $ageRange )    
	                                        <option value="{{$key }}"> {{ $ageRange }} </option>
	                                    @endforeach
                                    </select>
                                <?php }?>
                                <?php if(\Request::segment(1) == 'jobs'){
                                     ?>    
                                    <select id="influencer_age">
                                        <option value="0"> Age </option>
                                        @foreach( \App\Models\Job::ageFilter() as $key => $ageRange )    
                                            <option value="{{$key }}"> {{ $ageRange }} </option>
                                        @endforeach
                                    </select>
                                <?php }?>
                                    </span>
                                </div>
                            </div>

                            <hr class="filter-hr">

                            <div class="filters-content">
                                <h4> Location </h4>
                                <div class="filters-list_outer">
                                    <form onsubmit="return false">
                                        <input type="text" id="address" placeholder="Location" class="influencer_search">
                                        <input type="hidden" id="lat">
                                        <input type="hidden" id="lng">
                                    </form>
                                </div>
                            </div>

                            <hr class="filter-hr">

                            <div class="filters-content">
                                <h4> Radius of that Location </h4>
                                <div class="filters-list_outer">
                                    <div data-role="rangeslider">
                                        <label for="price-min">KM: <span id="radious">{{ @$radious }}</span></label>
                                        <div id="location-radious-range"></div>
                                    </div>
                                </div>
                            </div>
                   
                        </div>
                    </div>