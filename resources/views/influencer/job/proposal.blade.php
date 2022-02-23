{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')

<section class="common-form_wrapper lastsection-space_b bg-blue section-space d-none">
    <div class="container">
        <div class="common-form_outer">
            <div class="common-form_inner">
                <div class="common-form-content">
                    <h3 class="common-form_heading"> Submit Proposal </h3>
                    <form class="common-form" action="{{ url('job/'.$job['slug'].'/submit/proposal') }}" method="post" id="job-submit-proposal" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{@$job['id']}}">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="common-form_group">
                                    <label for="forlabel1"> Cost( $ )</label>
                                    <input type="number" class="form-control jobCostAmount" id="forlabel1" name="cost" value="{{old('cost')?: ( @$proposal['cost']?: $job->price ) }}" autocomplete="password_new" placeholder="Cost">
                                    <span id="error"></span>
                                    <input type="hidden" id="jobCosts" value="{{old('cost')?: ( @$proposal['cost']?: $job->price ) }}">
                                    @if ($errors->has('cost'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="cost" data-validator="notEmpty" class="fv-help-block error">
                                            {{ $errors->first('cost') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="common-form_group">
                                    <label for="forlabel2"> Cover Letter </label>
                                    <textarea type="textarea" id="forlabel2" name="cover_latter" class="ckeditor">{{old('cover_latter')?:@$proposal['cover_latter']}}</textarea>
                                    @if ($errors->has('cover_latter'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="cover_latter" data-validator="notEmpty" class="fv-help-block error">
                                            {{ $errors->first('cover_latter') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="common-form_group">
                                    <label for="forlabel5"> Attachments </label>
                                    <div id="mydocumentdropzone" class="dropzone">
                                        <div class="fallback">
                                            <input name="attachments" type="file" multiple id="attachments" />
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>

                                    <input type="hidden" id="proposal_attachments" name="proposal_attachments" value="{{old('attachments')?:@$proposal['attachments']}}">
                                    @if(empty($proposal->attachments))
                                    <input type="hidden" id="existing_roposal_attachments" value="{{@$proposal ? : '' }}">
                                    @else
                                    <input type="hidden" id="existing_roposal_attachments" value="{{@$proposal ? $proposal->getAttachments() : '' }}">
                                    @endif

                                    @if ($errors->has('proposal_attachments1'))
                                    <div class="fv-plugins-message-container">
                                        <div data-field="attachments" data-validator="notEmpty" class="fv-help-block">
                                            {{ $errors->first('proposal_attachments1') }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="common-btns">
                                    <div class="cancel-btn">
                                        <a href="{{ url('job/'.$job['slug']) }}" class="flex-center"> Cancel </a>
                                    </div>
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

<section class="accept-wrapper lastsection-space_b bg-blue section-space">
    <div class="container">
        <div class="accept-post-outer">
            <div class="accept-post-head">
                <img src="{{ $job->logo() }}" alt="">
                <h2>Accept to post</h2>
            </div>
            <div class="accept-post-body">
                <div class="row">
                    <div class="col-lg-7 col-md-6 accept-post-content">
                        <h4>File</h4>
                        <?php $extension = pathinfo(storage_path($job->image_video()), PATHINFO_EXTENSION);
                                                ?>
                        @if(@$extension=="mp4")
                            <video width="320" height="240" controls>
                                  <source src="{{ $job->image_video() }}" type="video/mp4">
                            </video>
                         @else
                              <img src="{{ $job->image_video() }}" alt="">
                           
                        @endif
                    </div>
                    <div class="col-lg-5 col-md-6 accept-post-content">
                        <h4>Caption</h4>
                        <p> {{ strip_tags($job->caption )}} </p>
                        <a class="post-button" href="{{ url('download-zip/'.$job['slug']) }}">Accept to post save files and caption</a>
                    </div>
                </div>
            </div>
            <div class="accept-post-footer">
                 <h5>Once you accept the job please copy the link of post and paste into the "post of influencer box" that way the owner can see the post Your funds will be released to you in 24 hours and the owner will give its rating. Thank you.</h5>
                 <form class="common-form" action="{{ url('job/'.$job['slug'].'/submit/post-url') }}" method="post" id="job-submit-post-url" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{@$job['id']}}" id="post_url_error">
                 <div class="text-center">
                    @if ($errors->has('url'))
                        <div class="fv-plugins-message-container">
                            <div data-field="url" data-validator="notEmpty" class="fv-help-block error">
                                {{ $errors->first('url') }}
                            </div>
                        </div>
                    @endif
                    @foreach($postUrl as $url)
                         @if($url->platform_id == 1)
                         <input class="link-text mygroup" name="url[1]" type="url" placeholder="Paste the instagram link here" value="{{ $job->getPostLinks($url->platform_id) }}" id="instagram_url">
                         @endif

                         @if($url->platform_id == 2)
                         <input class="link-text mygroup" name="url[2]" type="url" placeholder="Paste the facebook link here" value="{{ $job->getPostLinks($url->platform_id) }}" id="facebook_url">
                         @endif

                         @if($url->platform_id == 3)
                         <input class="link-text mygroup" name="url[3]" type="url" placeholder="Paste the youtube link here" value="{{ $job->getPostLinks($url->platform_id) }}" id="youtube_url">
                         @endif

                         @if($url->platform_id == 5)
                         <input class="link-text mygroup" name="url[5]" type="url" placeholder="Paste the twitter link here" value="{{ $job->getPostLinks($url->platform_id) }}" id="twitter_url">
                         @endif

                         @if($url->platform_id == 4)
                         <input class="link-text mygroup" name="url[4]" type="url" placeholder="Paste the tiktok link here" value="{{ $job->getPostLinks($url->platform_id) }}" id="tiktok_url">
                         @endif
                     
                    @endforeach
                     <div>
                        @if(@$hirejob)
                             @if(empty(@$reviews))
                                <a href="{{ url('job/reviews/'. Helpers::encrypt( $job['id'] ) ) }}" class="dark-btn"> Feedback User </a>
                             @endif
                        @else
                            @if(!$job->isCompleted())
                               <input id="job-post-url-submit" type="submit" value="Submit post & recieve your funds" class="highlight-funds">
                            @endif
                        @endif
                        


                        
                     </div>
                 </div>
             </form>
            </div>
        </div>
    </div>
</section>

@endsection