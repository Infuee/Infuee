{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content') 


    <section class="common-form_wrapper lastsection-space_b bg-blue section-space">

        <div class="container">
            <div class="common-form_outer">
                <div class="common-form_inner">
                    <div class="common-form-content">

                        


                        <h3 class="common-form_heading"> Testimonial </h3> 

                        <form class="common-form" action="{{ url('submit/testimonial') }}" method="post" id="job-submit-proposal" enctype="multipart/form-data">
                            @csrf
                            
                            
                            
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel1"> Name *</label>
                                        <input type="text" class="form-control" id="title" name="name" value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}"  placeholder="Title" readonly>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <div class="common-form_group">
                                        <label for="forlabel1"> Testimonial Description *</label>
                                        <textarea id="review" name="description" rows="4" cols="50"></textarea>
                                        <span id="error"></span>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-6">
                                    <div class="common-btns">
                                        <div class="create-btn flex-center  m-auto ms-sm-auto">
                                            <a id="skiptestimoinal" href="{{ url('my-jobs') }}" class="flex-center">Skip</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-6">
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