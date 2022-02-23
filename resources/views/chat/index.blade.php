{{-- Extends layout --}}
@extends('auth.layouts.auth')

{{-- Content --}}
@section('content')

<section class="chat-main_wrapper section-space lastsection-space_b bg-blue">

    <div class="container-lg">
        <div class="filterMenu filterMenu--mobile chat-m">
            <div class="filterMenuInner">
                <span></span>
                <span></span>
                <span></span>
            </div>

        </div>
        @if(count($connected))
        <div class="chat-outer">
            <div class="chat-inner h-100">
                <div class="row h-100">
                    <div class="col-12 col-md-5 col-lg-5 col-xl-4 col-sidebar pe-0">
                        <div class="chat-profile_sidebar">
                            <div class="chat-head meassage-header cu-user-information">
                                    <div class="chat-user user-live">
                                        <div class="chat-avatar">
                                            <img class="user-img_" src="{{ Auth::user()->getProfileImage() }}" alt="">
                                        </div>
                                        <div class="chat-name user-info">
                                            <h4 class="user-name_">{{ Auth::user()->first_name .' ' . Auth::user()->last_name }}</h4>
                                            <p class="user-job"></p>
                                        </div>
                                    </div>
                            </div>
                            <div class="chat-profile_list">
                                <ul id="connection_list" class="message-list list-wrap">
                                     
                                    @foreach($connected as $connect)
                                    @if(@$connect->user)
                                    <li class="chat-profile-info live-chats chat-list {{ $connect['active']?'active':'' }}" data-id="{{ $connect['chat_room_id'] }}" data-rid="{{Helpers::getRoomParticpantId($connect['chat_room_id']) }}" id="liveChats" data-chatS="chatoff">
                                        <input type="hidden" id="userParticpant" value="{{Helpers::getRoomParticpantId($connect['chat_room_id']) }}">
                                        <input type="hidden" id="userParticpantJobs{{Helpers::getRoomParticpantId($connect['chat_room_id']) }}" value="{{Helpers::getRoomParticpantId($connect['chat_room_id']) }}">
                                        <a class="@if($connect['user_active_status'] == 1)online @else  offline @endif time-ago" data-ids="{{ $connect['chat_room_id'] }}" onclick="ajudaUpload({{ $connect['chat_room_id'] }});return false;">
                                            <span class="chat-list_content">
                                                <span class="chat-person live-usr-img">
                                                    <img src="{{ @$connect->user->getProfileImage() }}" alt="">
                                                        <?php
                                                        $particanids = Helpers::getRoomParticpantId($connect['chat_room_id']);
                                                        ?>
                                                        
                                                    <span class="msg-count msg-count{{ $connect['chat_room_id'] }} message_count">
                                                    {{ \Helpers::unReadMsgCount($connect['chat_room_id'], $particanids) }} </span>
                                                </span>
                                                <span class="chat_detail chat-info">
                                                    <span class="chat-name live-usr-name"> {{ @$connect->getTitle() }} </span>
                                                    <span class="typeing{{ $connect['chat_room_id'] }}"></span>
                                                    
                                                    <span class="time-span"> {{ \Helpers::timeago( @$connect['lastchattime'] ) }} </span>
                                                    <span class="chat-text"> {{ @$connect['last_message'] ?: @$connect->user->getCategory() }} </span>
                                                </span>

                                            </span>
                                        </a>
                                    </li>
                                    @endif
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 ps-md-0">
                        <div class="chat-box_outer">
                            <div class="chat_box">
                                <div class="chat-head meassage-header">
                                    <div class="chat-user user-live">
                                        <div class="chat-avatar">
                                            <img class="user-img" src="" alt="">
                                        </div>
                                        <div class="chat-name user-info">
                                            <h4 class="user-name"></h4>
                                            <p class="user-job"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-body message-container messaging">

                                    <div class="chat-content_outer chating-area">
                                       
                                       <!--  -->
                                       <!-- <div class="messages receiver-msg">
                                            <div class="col-lg-1 col-2">
                                                <div class="user-img">
                                                    <img src="images/girl.jpg">
                                                </div>
                                            </div>
                                            <div class="message">
                                                <p>Hello</p>
                                                <p class="text-end"><small>02:10PM</small></p>
                                            </div>
                                       </div>

                                       <div class="messages receiver-msg">
                                            <div class="col-lg-1 col-2">
                                                <div class="user-img">
                                                    <img src="images/girl.jpg">
                                                </div>
                                            </div>
                                            <div class="message">
                                                <div class="document-section">
                                                    <div class="document-icon">
                                                        <img src="images/pdf-icon.png">
                                                    </div>

                                                    <div class="download-icon">
                                                        <a href=""><img src="images/download.png"></a>
                                                    </div>
                                                </div>
                                                <p class="text-end"><small>02:10PM</small></p>
                                            </div>
                                       </div>

                                       <div class="chat-seprator">
                                        <span>10th Sep, 2021, 10:00AM</span>
                                       </div>

                                        <div class="messages sender-msg">
                                            
                                            <div class="message">
                                                <p>Hello</p>
                                                <p class="text-end"><small>02:10PM</small></p>
                                            </div>
                                            <div class="col-lg-1 col-2">
                                                <div class="user-img text-end">
                                                    <img src="images/girl.jpg">
                                                </div>
                                            </div>
                                       </div>

                                       <div class="messages sender-msg">
                                            <div class="message">
                                                <div class="document-section">
                                                    <div class="document-icon">
                                                        <img src="images/pdf-icon.png">
                                                    </div>

                                                    <div class="download-icon">
                                                        <a href=""><img src="images/download.png"></a>
                                                    </div>
                                                </div>
                                                <p class="text-end"><small>02:10PM</small></p>
                                            </div>

                                            <div class="col-lg-1 col-2">
                                                <div class="user-img text-end">
                                                    <img src="images/girl.jpg">
                                                </div>
                                            </div>
                                       </div>-->
                                    </div> 
                                </div>
                                <div class="chat-foot">
                                    <div class="preview-outer">
                                        <!-- <div class="preview-inner">

                                         <span class="attach-img_c fileName1" >

                                            </span>
                                            
                                            


                                            
                                        </div> -->
                                        <input type="hidden" name="userId" id="userId" value="{{auth()->user()->id}}">
                                        <input type="file" name="file_name[]" id="file-input" style="display:none" />
                                            <div id="attach-file-div">
                                                <img class="uploded-img-before" src="" alt="">
                                            <span class="attach-file_c fileName" style="display:none;"> <a href="javascript:;"> file.doc </a> </span> 
                                            </div> 
                                            <p id="reply-to"></p>
                                            <p id="reply-back-div"><p class="cancel-message"><i class="fas fa-window-close"></i></p></p>
                                            
                                            <!-- <span class="progress"></span>
                                            <span class="img-content">
                                                <p class="sent-status"></p>
                                            </span> -->
                                    </div>
                                    <div class="chat-textarea">
                                        <input id="newMessages" type="text" name="writemessage" class="msg typeing" placeholder="Type your message..." value="" data-chatid=>
                                        <span class="attach"> <img src="images/attach-icon.png" alt=""> </span>
                                        <button type="submit" class="send-message"> <img src="images/send-icon.png" alt=""> </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="chat-inner">
            <div class="row">
                <div class="col-12 pe-0">
                    <div class="">
                        <h3 class="no-conversation text-center">You haven't initiated any conversation yet.</h3>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection
