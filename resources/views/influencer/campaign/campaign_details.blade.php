{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 
@php
$user = auth()->user();
@endphp
    <section class="agencies-main campaign-details_wrapper section-space bg-blue lastsection-space_b">
        <div class="agencies-outer activejob-outer">
            <div class="container-lg">
                <div class="agencies-inner editcampaign-inner">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="agencies-box editcampaign-box pb-0 mb-0">
                                <div class="agencies-logo">
                                    <img src="{{ $campaign->logo() }}" alt="">
                                </div>
                                <div class="agencies-detail">
                                    <div class="d-flex flex-row justify-content-between align-items-center">
                                        <h3> {{ $campaign['title'] }} </h3>
                                    </div>
                                    {!! $campaign['description'] !!}
                                </div>
                                @if(auth()->check() && @$campaign['created_by'] == auth()->id())
                                <div class="edit-campaign ms-auto">
                                    @if(count($campaign['jobsCount']) > 0)
                                    <p class="status"> Active Jobs: <span class="text-white"> {{ count($campaign['jobsCount']) }} </span> </p>
                                    @else
                                    <p class="status"> Active Jobs: <span class="text-white">No Active Job </span> </p>
                                    @endif
                                    <a href="{{ url('edit/campaign/'.$campaign['slug'] ) }}" class="text-white"> <i class="far fa-edit"></i> Edit Campaign
                                    </a>
                                    <a href="{{ url('wallet') }}" class="text-white wallet"> 
                                        <img src="{{ Helpers::asset('images/Wallet.png') }}" alt="" class="me-2"> 
                                        Wallet <i class="fas fa-plus ms-2" aria-hidden="true"></i></a>
                                    <p>Wallet Amount : ${{ $user->walletAmount() }} </p>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <hr class="active-border_hr">

                <div class="agencies-inner activejob-inner">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="active-heading">
                                <h3> Active Jobs </h3>
                                @if( @$campaign['created_by'] == auth()->id())
                                    <div class="create-jobs text-right">
                                        <a href="{{ url('campaign/'.$campaign['slug'].'/create/job') }}" class=""> <span class="flex-center"> <i
                                                    class="fas fa-plus"></i> </span>
                                            Create Jobs </a>
                                    </div>
                                @endif
                            </div>
                            
                            @foreach($campaign['jobs'] as $job)

                            <div class="agencies-box activejob-box">
                                <div class="agencies-logo">
                                    <img src="{{ $job->logo() }}" alt="">
                                </div>
                                <div class="agencies-detail">
                                    <a href="{{ url('job/'. $job['slug'] ) }}"><h3> {{ $job->title }}</h3></a>
                                    <div class="agencies-job-description">{{ Str::limit(strip_tags($job->description, 350)) }} </div>
                                    <div class="status-details d-flex flex-row justify-content-start align-items-center flex-wrap">
                                        <span class="min-span">Influencers:
                                            <span class="text-white"> {{ $job->influencers }} </span> 
                                        </span>
                                        <span class="min-span"> Platforms:
                                            <span class="text-white active-social_link"> {!! $job->getPlatformsHTML() !!} </span>
                                        </span>
                                        <span class="min-span"> Duration Time:
                                            <span class="text-white"> {{ $job->getMinutes()? $job->getMinutes().' min':'' }} {{ $job->getSeconds()? $job->getSeconds().' sec':'' }} </span> 
                                        </span>
                                        <span class="min-span"> Duration Days:
                                            <span class="text-white"> {{ $job->promo_days }} Days </span> 
                                        </span>
                                        <span class="min-span"> Price:
                                            <span class="text-white"> ${{ number_format($job->price, 2) }}/Influencer </span> 
                                        </span>
                                        <span class="min-span">Posted:
                                            <span class="text-white"> {{ $job['created_at'] ? $job['created_at']->diffForHumans() : ' '}} </span>
                                        </span>
                                    </div>
                                </div>

                                <div class="price-view">
                                    
                                    @if( Auth::check() && @$job['created_by'] == auth()->id())
                                    <div class="campaign-title_icons">
                                        <span> <a href="{{ url('campaign/'.$campaign['slug'].'/edit/job/'.$job->slug) }}"><i class="fas fa-pencil-alt"></i> </a></span>
                                        <span> <a href="javascript:void(0)" class="change-status" item="Job" url="{{ url('job/'.$job['slug'].'/delete') }}" operation="delete"> <i class="far fa-trash-alt"></i> </a></span>

                                        <label class="switch">
                                            <input class="change-status" item="Job" url="{{ url('job/'.$job['slug'].'/status') }}" operation="{{$job->status == 1 ? 'draft' : 'publish'}}" status="{{$job->status == 1 ? 'Deactivate' : 'Activate'}}" type="checkbox" {{ $job->status == \App\Models\Job::STATUS_ACTIVE ? 'checked' : '' }} >
                                            <span class="slider round"></span>
                                        </label>

                                    </div>
                                   
                                    @endif
                                    <p> ${{ number_format($job->price,2) }} </p>
                                    <div class="view-price_btn"> <a href="{{ url('job/'. $job['slug'] ) }}"> View </a> </div>
                                </div>
                            </div>

                            @endforeach
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection