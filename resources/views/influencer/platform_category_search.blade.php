    <div class="row">
          @if(count($platform_catogery))
                @foreach($platform_catogery as $platform_cat)
                      
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="category-box">
                                <a href="{{url('influencer')}}/{{@$platform_cat->username?@$platform_cat->username:@$platform_cat->id}}/profile">
                                <div class="category-icon_box d-flex flex-center flex-column"> 
                                	<div class="profile-img_box center-img">
		                            	
                                            <span class="price-span">${{\Helpers::getUserPlan($platform_cat->id)}} </span>
			                            	@if(@$platform_cat->image)
			                                <img src="{{ Helpers::asset('media/users/'.@$platform_cat->image) }}" alt="">
			                                @else
			                                 <img src="media/users/blank.png" alt="">
			                                @endif
		                                
                                    </div>
                                   <h4>{{@$platform_cat->first_name}} </h4>
                                   <p> {{@$platform_cat->name}}</p>
                                </div>
                            </a>
                                <div class="category-link">
                                    <a href="{{url('influencer')}}/{{@$platform_cat->username?@$platform_cat->username:@$platform_cat->id}}/profile"> View <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                    </div>
                @endforeach
                @else
                    <div class="row">
                        <div class="col-md-12 col-sm-12 text-center">
                            <img src="media/images/No_result.png">
                            <h3 class="no_order">No Result Found</h3>
                        </div>
                    </div>
            @endif    
        </div>