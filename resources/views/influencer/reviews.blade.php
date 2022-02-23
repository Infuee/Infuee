{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           

    <div class="browse_influence_block space_work">
                <div class="container">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            @include('user-flash-message')


                            <div class="view_account">

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="profile_detail user-reviews-heading">
                                            <h3>{{count($ratings)}} Reviews</h3>
                                            <div class="row">
                                                @if(count($ratings) > 0)
                                                    @foreach($ratings AS $rating)
                                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                                        <div class="user-review-list">
                                                        <div class="row">
                                                            <div class="col-md-1 col-sm-2 col-xs-3">
                                                                @php
                                                                    if(!file_exists(base_path().'/public/media/users/'.$rating->user->image) || empty($rating->user->image) ){                                        
                                                                        $image = 'default.jpg';
                                                                    } else{
                                                                        $image = $rating->user->image;
                                                                    }
                                                                    @endphp
                                                                    <img class="review_user_image" src="{{url('media/users/'.@$image)}}">
                                                            </div>
                                                            <div class="col-md-11 col-sm-10 col-xs-9">
                                                                <h2>{{$rating->user->first_name.' '.$rating->user->last_name}}</h2>
                                                                   <h4>{{@$rating->orderItem->userPlan->allPlan->allCategory->name}} ({{@$rating->orderItem->userPlan->allPlan->name}})</h4>
                                                                   <!-- <h3>{{@$rating->order->order_items->userPlan->allPlan->name}}</h3> -->
                                                                   <div class="rating rating_view">
                                                                        <input type="radio" id="star5" name="rating{{$rating->id}}" value="5" {{($rating->rating >= 5 ? 'checked':'' )}} /><label class = "full {{($rating->rating >= 5 ? 'checked':'' )}} " for="star5" title="Awesome - 5 stars"></label>
                                                                        <input type="radio" id="star4half" name="rating{{$rating->id}}" value="4.5" {{($rating->rating >= 4.5 ? 'checked':'' )}} /><label class="half {{($rating->rating >= 4.5 ? 'checked':'' )}}" for="star4half" title="Pretty good - 4.5 stars"></label>
                                                                        <input type="radio" id="star4" name="rating{{$rating->id}}" value="4" {{($rating->rating >= 4 ? 'checked':'' )}} /><label class = "full {{($rating->rating >= 4 ? 'checked':'' )}} " for="star4" title="Pretty good - 4 stars"></label>
                                                                        <input type="radio" id="star3half" name="rating{{$rating->id}}" value="3.5" {{($rating->rating >= 3.5 ? 'checked':'' )}} /><label class="half {{($rating->rating >= 3.5 ? 'checked':'' )}}" for="star3half" title="Meh - 3.5 stars"></label>
                                                                        <input type="radio" id="star3" name="rating{{$rating->id}}" value="3" {{($rating->rating >= 3 ? 'checked':'' )}} /><label class = "full {{($rating->rating >= 3 ? 'checked':'' )}} " for="star3" title="Meh - 3 stars"></label>
                                                                        <input type="radio" id="star2half" name="rating{{$rating->id}}" value="2.5" {{($rating->rating >= 2.5 ? 'checked':'' )}} /><label class="half {{($rating->rating >= 2.5 ? 'checked':'' )}}" for="star2half" title="Kinda bad - 2.5 stars"></label>
                                                                        <input type="radio" id="star2" name="rating{{$rating->id}}" value="2" {{($rating->rating >= 2 ? 'checked':'' )}} /><label class = "full {{($rating->rating >= 2 ? 'checked':'' )}} " for="star2" title="Kinda bad - 2 stars"></label>
                                                                        <input type="radio" id="star1half" name="rating{{$rating->id}}" value="1.5" {{($rating->rating >= 1.5 ? 'checked':'' )}} /><label class="half {{($rating->rating >= 1.5 ? 'checked':'' )}}" for="star1half" title="Meh - 1.5 stars"></label>
                                                                        <input type="radio" id="star1" name="rating{{$rating->id}}" value="1" {{($rating->rating >= 1 ? 'checked':'' )}} /><label class = "full {{($rating->rating >= 1 ? 'checked':'' )}} " for="star1" title="Sucks big time - 1 star"></label>
                                                                        <input type="radio" id="starhalf" name="rating{{$rating->id}}" value="0.5" {{($rating->rating >= 0.5 ? 'checked':'' )}} /><label class="half {{($rating->rating >= 0.5 ? 'checked':'' )}}" for="starhalf" title="Sucks big time - 0.5 stars"></label>
                                                                        <div class="text-left">
                                                                            <label for="name" class="error error_rating" style="display: none;">Please enter rating</label>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                       
                                                       <p>{{$rating->review}}</p>
                                                       </a>
                                                    </div>
                                                    </div>
                                                    <br>
                                                    @endforeach
                                                @else
                                                    <h2>No user reviewed yet.</h2>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            
                        </div>
                    </div>
                </div>
            </div>
@endsection
