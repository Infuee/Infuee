
                    @foreach($platform_catogery_data as $platform_cat)
                      
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4 cat_box">
                            <div class="category-box">
                                <div class="category-icon_box d-flex flex-center flex-column"> 
                                	<div class="profile-img_box center-img">
		                            	<a href="{{url('influencer')}}/{{@$platform_cat['username']}}/profile">
                                            <span class="price-span"> $120 </span>
			                            	@if(@$platform_cat['image'])
			                                <img src="{{ Helpers::asset('media/users/'.@$platform_cat->image) }}" alt="">
			                                @else
			                                 <img src="media/users/blank.png" alt="" style="height: 100px; width: 100px;">
			                                @endif
		                                </a>
                                    </div>
                                   <h4> {{'@'.@$platform_cat['username']}} </h4>
                                   <p> {{@$categoryName->name}}</p>
                                </div>
                                <div class="category-link">
                                    <a href="{{url('influencer')}}/{{@$platform_cat['username']}}/profile"> View <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    @endforeach
                         