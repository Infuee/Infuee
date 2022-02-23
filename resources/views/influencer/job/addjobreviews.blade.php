{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 


    <section class="common-form_wrapper lastsection-space_b bg-blue section-space">

        <div class="container">
            <div class="common-form_outer">
                <div class="common-form_inner">
                    <div class="common-form-content">

                        


                        <h3 class="common-form_heading"> Submit Your Reviews </h3>

                        <form class="common-form" action="{{ url('job/submitreviews') }}" method="post" id="job-submit-proposal" enctype="multipart/form-data">
                            @csrf
                            
                            <input type="hidden" name="jobId" value="{{ $job_id }}">
                            @if(auth()->user()->type == 2)
                            <input type="hidden" name="userTO" value="{{ $userBy->created_by }}">
                            <input type="hidden" name="userBy" value="{{ $userBy->influencers_id }}">
                            @else
                            <input type="hidden" name="userBy" value="{{ $userBy->created_by }}">
                            <input type="hidden" name="userTO" value="{{ $userBy->influencers_id }}">
                            @endif
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel1"> Title* </label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ $userBy->title }}"  placeholder="Title" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel1"> Rating* </label>
                                        <div class="star-rating">
                                            <input type="radio" id="5-stars" name="starrating" value="5" />
                                            <label for="5-stars" class="star">&#9733;</label>
                                            <input type="radio" id="4-stars" name="starrating" value="4" />
                                            <label for="4-stars" class="star">&#9733;</label>
                                            <input type="radio" id="3-stars" name="starrating" value="3" />
                                            <label for="3-stars" class="star">&#9733;</label>
                                            <input type="radio" id="2-stars" name="starrating" value="2" />
                                            <label for="2-stars" class="star">&#9733;</label>
                                            <input type="radio" id="1-star" name="starrating" value="1" />
                                            <label for="1-star" class="star">&#9733;</label>
                                            </div>
                                    </div>
                                </div>


                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel1"> Feedback* </label>
                                        <textarea id="review" name="review" rows="4" cols="50"></textarea>
                                        
                                    </div>
                                </div>

                                <!---div class="col-sm-12">
                                    <div class="common-form_group">
                                        <select class="star-rating" name="starrating">
                                            <option value="">Select a rating</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div---->


                                <div class="col-sm-12">
                                    <div class="common-btns">
                                        
                                        <div class="create-btn flex-center">
                                            <input id="job-submit-proposal-submit" type="submit" value="Submit" class="flex-center">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection