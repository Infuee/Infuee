{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')           
     <section class="account-profile_main section-space bg-blue lastsection-space_b">
        <div class="account-profile_outer">
            <div class="container-lg">
                <div class="account-profile_inner">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="user-profile_content pb-0 my-3">
                                <div class="user-logo">
                                    <img src="{{ $user->getProfileImage() }}">
                                </div>
                                <div class="account-profile">
                                    <div class="head-inner_text">
                                        <h3> {{ ucwords(@$user['first_name'] . ' ' . @$user['last_name']) }} </h3>
                                        <div class="edit-account ms-auto">
                                            <a href="{{url('profile-settings')}}" class="btn btn-light"> 
                                                <i class="far fa-edit"></i> 
                                                Edit Account Info
                                            </a>
                                        </div>
                                    </div>
                                    <p class="skin-text"> {{@$user->getCategory()}} </p>
                                </div>
                            </div>
                            <p class="profile-description_p"> {{@$user->description}} </p>
                        </div>
                    </div>
                </div>


                <hr class="active-border_hr">
                <!-- --- -->
                @if(count($orderItems))
                <div class="">
                    <div class="order-inner">
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="order-heading">
                                    <h3> My Orders </h3>
                                    <div class="browse-infue ms-auto text-right">
                                        <a href="{{url('influencers')}}" class=""> 
                                            Browse Influencer  </a>
                                    </div>
                                </div>
                                @foreach($orderItems as $item)
                                <div class="order-box">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-9 order-box-item">
                                            <div class="agencies-logo">
                                                @if(is_file(public_path("/media/users").'/'.@$item['userPlan']['influencer']['image']))
                                                    <img src="{{asset('media/users/'.$item['userPlan']['influencer']['image'])}}">
                                                @else
                                                    <img src="media/users/blank.png">
                                                @endif
                                            </div>
                                            <div class="order-detail">
                                                <h3> {{$item['userPlan']['plan']['name']}} {{$item['userPlan']['plan']['category']['name']}} with {{'@'.$item['userPlan']['influencer']['first_name']}} <span class="order-head_date"> {{ date( 'd F, Y', strtotime($item['created_at'])) }} </span> </h3>
                                                <p class=""> {!! $item['order']['description'] !!} </p>
                                               
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-3 price-view text-end">
                                            <p> ${{number_format(@$item['userPlan']['price'],2)}} </p>
                                            <div class="buy-btn "> <a href="{{url('addtocart')}}/{{\Helpers::encrypt($item['userPlan']['id'])}}"> Buy Again </a> </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            
                            </div>
                            
                        </div>
                    </div>

                </div>
                @endif
                <!-- --- -->
            </div>
        </div>
    </section>
@endsection