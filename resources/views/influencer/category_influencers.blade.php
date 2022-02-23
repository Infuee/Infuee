{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 

    <section class="category-main section-space bg-blue lastsection-space_b">
        <div class="category-outer">
            <div class="container">
                <div class="common-heading text-center">
                    <h2> Select Category for {{ $category['name'] }} Influencers </h2>
                </div>
                <div class="category-inner">
                    <div class="row">
                        @foreach($platform_catogery as $platform_cat)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="category-box">
                                <div class="category-icon_box d-flex flex-center flex-column inner-padding">
                                @if(@$platform_cat->image)
                                    <img src="{{ Helpers::asset('uploads/category/'.@$platform_cat->image) }}" alt="">
                                @else
                                    <img src="media/users/blank.png" alt="" >
                                @endif
                                    <p> {{$platform_cat->name}} </p>
                                    <p>{{\Helpers::userCount($platform_cat->id,$platform_cat->platform_id)}} Influencers</p>
                                </div>
                                <div class="category-link">
                                    <a href="{{ url('influencers/'.$category['name'].'/'.$platform_cat->slug.'/'.$platform_cat->id) }}"> 
                                        Promotion on {{ $category['name'] }} 
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection