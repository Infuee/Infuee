const socket = io(SOCKET_URL);

var reply_id = 0;
var uploader = new SocketIOFileClient(socket);
var files = false;
var userParticapant = $('#userParticpant').val();
var usrPartipntIdJob = $('#userParticpantJobs'+userParticapant).val();
var currUserId = $('#userId').val();
$('.cancel-message').hide();
$('#connection_list .active').trigger("click");

uploader.on('start', function(fileInfo) {
    console.log('Start uploading', fileInfo);
});
uploader.on('stream', function(fileInfo) {
    console.log('Streaming... sent ' + fileInfo.sent + ' bytes.', fileInfo );
    var filename = (fileInfo.name).replace('.', '');
    var size = fileInfo.size;
    var sent = fileInfo.sent;
    var percent = parseInt((sent*100)/size) ;
    console.log('filenamefilenamefilename', filename );
    // if(percent <= 50){
    //   var deg = (180/100)*(percent * 2 ) ;
    //   $('.'+filename+' .progress .progress-right .progress-bar').css('transform','rotate('+deg+'deg)');
    // }else{
    //   var deg = (180/100)*percent ;
    //   $('.'+filename+' .progress .progress-right .progress-bar').css('transform','rotate(180deg)');
    //   $('.'+filename+' .progress .progress-left .progress-bar').css('transform','rotate('+deg+'deg)');
    // }
});
uploader.on('complete', function(fileInfo) {
    console.log('Upload Complete', fileInfo);
    var filename = (fileInfo.name).replace('.', '');
    console.log("many",filename);
    $('.progress').attr('data-percentage', '100');
    setTimeout(function(){
      $('.progress_block').remove();
    },1000)
    var date = new Date();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    updateFileStatus(fileInfo);
});
uploader.on('error', function(err) {
    console.log('Error!', err);
});
uploader.on('abort', function(fileInfo) {
    console.log('Aborted: ', fileInfo);
});


socket.on('connect', function () {
    var connect =  {"id":logged_in_user};
    console.log('connected fff',connect.id);
    socket.emit('user-active', connect);
});

socket.on('user_active_list', function (res) {
   
    console.log('connected fffsdfsdfsdfd',res.receiver_id);

    $("#connection_list .chat-list").each(function () { 
         var chatIds = $(this).attr('data-id');
         console.log("chat console",chatIds) 
         if(chatIds == res.receiver_id){
         $('.offline').addClass('useractive');
         } else {
          $('.offline').removeClass('useractive');

         }

    })
  
});

socket.on("disconnect", () => {
  console.log("userdisconnect",socket.id); // undefined
});
socket.on('file-sent', function (data) {
  console.log('file-sent');
  appendMessage(data, true);
});
socket.on('chat-message', function (data) {
  var r_id = $('#connection_list .active').data('id');
  console.log('newMessagesdata', r_id, data );
 
  var myParticipant_id = $('#connection_list .active').data('rid');
  console.log("unreads",data)
  appendMessage(data, true, true );
  if(data.receiver_id == r_id){
     // $('.msg-count13').text('0');
    if(data.participant_id  = myParticipant_id) {
      socket.emit('mark_seen', data );
    }
  }else{
    // $('.msg-count'+data.receiver_id).text(data.unreadMsgCount);
  }

});

 socket.on('count_message',function (data) {
  console.log("count",data)
    if(data.recevier_id ==  userParticapant){
         //$('.msg-count'+data.chatroom_id).text(data.unreadMsgs);
    }
 })
function ajudaUpload(d){
  $('.msg-count'+d).text('');
}

socket.on('typing_status', function (data) {

//console.log('teststatus1',data);
var chatIdType = $('#newMessages').attr('data-chatid');
 $("#connection_list .chat-list").each(function () { 
    var chatIds = $(this).attr('data-id');
    //console.log('dataids',data.connection_id,'typeing'+chatIds) 
    if(chatIds == data.connection_id && logged_in_user != data.user_id ){
    $(this).find('.typeing'+chatIds).addClass('typingStatus'+chatIds);
    $('.typingStatus'+chatIds).show();
      $('.typingStatus'+chatIds).html(data.status);
      setTimeout(function () {
        $('.typingStatus'+chatIds).hide();
      },4000);
    } else {
      setTimeout(function () {
        //socket.emit('typing_end', data);
      },4000);
    }
 })

var messType = $('#newMessages').attr('data-chatid');
//console.log("asfsdf", data.connection_id);
 if(messType == data.connection_id  && logged_in_user != data.user_id){
  $("#newMessages").addClass('typeing'+messType);
  $('.typeing'+messType).attr('placeholder',data.status);
  setTimeout(function () {
        $('.typeing'+messType).attr('placeholder',"Type your message...");
      },4000);
 } else{
  $('.typeing'+messType).attr('placeholder',"Type your message...");
 }


});

$(document).on('change', '#file-input', function(){
  var connection = $('#connection_list .active').data('id');
    var filePath = $(this).val();
    var fileName = filePath.substring(filePath.lastIndexOf('\\')+1);
    console.log('filePath=='+filePath);
    var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
    
    $('.fileName').html(fileName);
    $this_ = this ;
    files = $this_;
    if (allowedExtensions.exec(filePath)) {
      $('.uploded-img-before').show();
      $('#attach-file-div').show();
      $('#preview-file-icon').hide();
      var reader = new FileReader();
   
      imageUploaded = true;
      reader.onload = function (e) {
      $('.uploded-img-before').show();
        $('#attach-file-div' ).show();
        //console.log("imageupoaddddd",e.target.result)
        $('.uploded-img-before').attr('src', e.target.result).width('10%').height('10%').show();
      };
      
      reader.readAsDataURL($this_.files[0]);
    }else{
      $('#attach-file-div' ).show();

      $('#preview-file-icon').show();
      $('.uploded-img-before').hide();
    }
});




$('#connection_list .live-chats').on('click', function(){
  $(".live-chats").each(function( index ) {
     $(this).removeClass('active');
  });
  $(this).addClass('active');
  var ele = this ;
  var image = $(ele).find('.live-usr-img img').attr('src');
  var username = $(ele).find('.chat-info .live-usr-name').text();
  var userId = $(ele).data('id') ;
  userParticapant = $(ele).find('#userParticpant').val();

  //$('.msg-count'+userId).text('0');

  $('#newMessages').attr('data-chatid',userId);
  $('.meassage-header .user-live .user-img').attr('src', image);
  $('.meassage-header .user-live .user-info .user-name').text(username);
  var connect =  {"id":logged_in_user,"type":"staff", "receiver_id":userId};
  //console.log("heyyyyyy",connect.receiver_id);

  socket.emit('user-join', connect);

  loadUserDetails(userId);
}).click();



  

$(document).on('click', '.reply-msgs', function(e) {
  $('.cancel-message').show();
  $sentMessage = $(this).parent().find('.chatting-message').html();
  console.log('helllloo replymsg', $sentMessage );
  var id =  $(this).data('id');
  reply_id = id;
  $('#reply-to').attr('data-id', id);
  $('#reply-to').html($sentMessage);
  $('#reply-back-div').show();

});
$(document).on('click', '.cancel-message', function(e) {
  $('#reply-back-div').hide();
  $('#attach-file-div').hide();
  $('.cancel-message').hide();
  files = false;
  $('#reply-to').html('');
  reply_id = 0;
});


$(document).on('keyup', '#newMessages', function(e) {
    var r_id = $('#connection_list .active').data('id');
  
  var data = {
      connection_id : r_id,
      sender_id:logged_in_user
  };

  typingStatus(data);
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if(keycode == '13'){
      $('.send-message').click();
  }
  console.log('kk'+$(this).val());

});
$(document).on('click', '.send-message', function(e) {
  
  $inputMessage = $('#newMessages');
  var receiverIds = $('#connection_list .active').attr('data-rid');
  console.log($inputMessage, $inputMessage.val().trim() );
  sendMessage($inputMessage.val().trim(),receiverIds);
});


function typingStatus(data){
    socket.emit('typing_start', data);
}
function sendMessage(message,receiverIds) {
    
    if(message == '' && (!files || files.files.length <= 0)){
      $('#message-error').text('Please enter message.');
      return false;
    }
    var participantIds =  receiverIds;
    $inputMessage.val('');
    $inputMessage.html('');
    $inputMessage.focus()

    var r_id = $('#connection_list .active').data('id');
    var p_id = $('#connection_list .active').data('rid');
    console.log("activeids",r_id)
    var reply = $('#reply-to').data('id');
    var replymsg = $('#reply-to').html();
    var data = {
        time: new Date().getTime(),
        sender_id : p_id,
        //sender_id : userParticapant,
        receiver_id : r_id,
        user_id : currUserId,
        message: message,
        reply_id:reply_id,
        file_type:'',
        reply_message: replymsg,
        plateform :'website',
    };
   
    //console.log('files', files.files['0'].name );
    if(files && files.files.length > 0){
      var fileName = files.files['0'].name;
      var fileExt = fileName.substr( (fileName.lastIndexOf('.') +1) );
      var data1 = {
        time: new Date().getTime(),
        sender_id : p_id,
        //sender_id : userParticapant,
        user_id : currUserId,
        receiver_id : r_id,
        message: message,
        reply_id:reply_id,
        file_type:fileExt,
        reply_message: replymsg,
        plateform :'website',
    };
      var uploadIds = uploader.upload(files, {
        user_id : currUserId,
        data: data1
      });

      $('#attach-file-div').hide();
      files = false;
      console.log('uploadIds==='+uploadIds);
      return;
    }
    socket.emit('chat-message', data);
    
    $inputMessage.val('');
}

lastDate = false;
mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
today = new Date();
today_year = today.getFullYear();
today_month = today.getMonth();
today_day = today.getDate();
yesterday = new Date();
yesterday.setDate(today.getDate() - 1);
yester_year = yesterday.getFullYear();
yester_month = yesterday.getMonth();
yester_day = yesterday.getDate();
isSentLastImage = false;
isRecLastImage = false;

chat = '.messaging .chating-area';
function updateFileStatus(fileInfo){
  //console.log(fileInfo.name);
  $(".img-content .sent-status."+(fileInfo.name).replace('.', '')).text('Sent');
}
function appendMessage(message, isLast, append = true){

  var html = "";
  var date = new Date( typeof message.time === 'undefined' ? message.created_at : message.time );
  var year = date.getFullYear();
  var month = date.getMonth();
  var day = date.getDate();
  var day_ = day;
  if (day < 10) {
      day_ = "0" + day;
  }
  var date_ = year +'/'+month+'/'+day_ ;
 
  if(lastDate != date_){
    lastDate = date_ ;
    var display_date = mlist[month] + ' ' + day + ', '+ year ;
    if(year == today_year && month == today_month && day == today_day){
      display_date = 'Today';
    }else if(year == yester_year && month == yester_month && day == yester_day){
      display_date = 'Yesterday';
    }
    html +=  `<div class="chat-date"><p>`+ display_date +`</p></div>` ;
  }

  var hr = date.getHours();
  var min = date.getMinutes();
  if (min < 10) {
      min = "0" + min;
  }
  var ampm = "AM";
  if( hr > 12 ) {
      hr -= 12;
      ampm = "PM";
  }
  var time = hr + ':' + min + ' '+ampm;
  var msg = message.message ? message.message : '';

  
    isSentLastImage = false;
    isRecLastImage = false;
    
   var newjobpartntId = $('#userParticpantJobs'+message.participant_id).val();
  
   if(message.participant_id == userParticapant || message.participant_id == newjobpartntId){
  
      if(message.reply_id ){
        var reply = $('#reply-to').html();
        html += '<div class="outgoing"><div class="bubble 2"><div class="messaging-col"><div class="reply-quotes"><div class="reply-back"><div class="ping"></div><div class="ping-msg">' +(message.reply_message ? message.reply_message.replace(/\n/g, "<br />") : '<img src="uploads/chat/'+message.child.file_name+'">' ) +'</div></div><div class="chatting-message outgoing" >'+(msg.replace(/\n/g, "<br />"))+'</div><span class="reply-msgs" data-id="'+message.id+'"><i class="fa fa-reply" aria-hidden="true"></i></span><div class="message-time">'+time+'</div></div></div></div></div>';
      
        $('#reply-to').html('');
        $('#reply-to').attr('data-id', '');
        $('#reply-back-div').hide();

      }else if( message.file_name){
       console.log('XXXX5')
     
        html += '<div class="messages sender-msg">\
                      <div class="message">\
                          <div class="document-section">\
                              <div class="document-icon">';
                                if(  message.file_type == 'jpeg' || message.file_type == 'png' ||message.file_type == 'svg' ||message.file_type == 'webp' || message.file_type == 'jpg' || message.file_type == 'gif' ){
                                    html += '<img src="uploads/chat/'+message.file_name+'" alt="'+message.file_name+'" />';
                                }else{
                                    html += '<img src="images/pdf-icon.png">';
                                }
                              html += '</div>\
                              <div class="download-icon">\
                                  <a href=""><img src="images/download.png"></a>\
                              </div>\
                          </div>\
                          <p>'+(msg.replace(/\n/g, "<br />"))+'</p>\
                          <p class="text-end"><small>'+time+'</small></p>\
                      </div>\
                      <div class="col-lg-1 col-2">\
                          <div class="user-img text-end">\
                              <img src="'+ message.sender_image +'">\
                          </div>\
                      </div>\
                 </div>';


      }else{
      
              html += '<div class="messages sender-msg">\
                          <div class="message">\
                              <p>'+(msg.replace(/\n/g, "<br />"))+'</p>\
                              <p class="text-end"><small>'+time+'</small></p>\
                          </div>\
                          <div class="col-lg-1 col-2">\
                              <div class="user-img text-end">\
                                  <img src="'+message.sender_image+'">\
                              </div>\
                          </div>\
                     </div>';
      }

    }else{
      if(message.reply_id){
        var reply = $('#reply-to').html();
      
        html += '<div class="messaging-col"><div class="reply-quotes incomming"><div class="reply-back"><div class="ping"></div><div class="ping-msg">' +(message.reply_message ? message.reply_message.replace(/\n/g, "<br />") : '<img src="uploads/chat/'+message.child.file_name+'">' ) +'</div></div><div class="chatting-message incomming" >'+(msg.replace(/\n/g, "<br />"))+'</div><span class="reply-msgs" data-id="'+message.id+'"><i class="fa fa-reply" aria-hidden="true"></i></span><div class="message-time">'+time+'</div></div></div>';
      
        $('#reply-to').html('');
        $('#reply-to').attr('data-id', '');
        $('#reply-back-div').hide();

      }else if( message.file_name  ){

        html += '<div class="messages receiver-msg">\
                    <div class="col-lg-1 col-2">\
                        <div class="user-img">\
                            <img src="'+ message.sender_image +'">\
                        </div>\
                    </div>\
                    <div class="message">\
                        <div class="document-section">\
                            <div class="document-icon">';
                                if(  message.file_type == 'jpeg' || message.file_type == 'png' ||message.file_type == 'svg' ||message.file_type == 'webp' || message.file_type == 'jpg' || message.file_type == 'gif' ){
                                    html += '<img src="uploads/chat/'+message.file_name+'" alt="'+message.file_name+'" />';
                                }else{
                                    html += '<img src="images/pdf-icon.png">';
                                }
                              html += '</div>\
                            <div class="download-icon">\
                                <a href="uploads/chat/'+message.file_name+'"><img src="images/download.png"></a>\
                            </div>\
                        </div>\
                        <p>'+ (msg.replace(/\n/g, "<br />")) +'</p>\
                        <p class="text-end"><small>'+time+'</small></p>\
                    </div>\
               </div>';
      }else{

        html += '<div class="messages receiver-msg">\
                      <div class="col-lg-1 col-2">\
                          <div class="user-img">\
                              <img src="'+ message.sender_image +'">\
                          </div>\
                      </div>\
                      <div class="message">\
                          <p>'+ (msg.replace(/\n/g, "<br />")) +'</p>\
                          <p class="text-end"><small>'+time+'</small></p>\
                      </div>\
                 </div>';
      }
    }
  
  if(append === true){
    $(chat).append(html);
    if(isLast){
      $(".message-container").stop().animate({ scrollTop: $('.chating-area')[0].scrollHeight }, 'slow');
    }
  }else{
    $(".message-container").stop().animate({ scrollTop: $('.chating-area')[0].scrollHeight }, 'slow');
    
  }

}


function loadUserDetails(userId){

  $.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "post",
    url: '/api/getUserDetails',
    data: {userId:userId},
    success: function(data) {
        console.log("helkkdj slfkj", data.chat);

        $('.meassage-header .head-revw .rev-total').html(data.reviews + " <span>Reviews</span>");
        $('.meassage-header .head-revw .rev-scalle span').html(data.rating);
        $('.meassage-header .head-revw .current-location').html(data.country);

        appendMessages(data.chat);

    },
    error: function(err){
        console.log('err'+JSON.stringify(err));


    }
});

}


var messagesLength = 0;
function appendMessages(messages, append = true){

  if(append === true)
    $('.chating-area').html('');
  messagesLength = messages.length;
  $.each(messages,function(key,value){
    if(value.reply_id){
    value.reply_message=value.child.message;
    }else{
    value.reply_message='';
    }

    var isLast = key == messages.length - 1;
    appendMessage(value, isLast, append);

  });

  // $(".chat-box").stop().animate({ scrollTop: $(".chat-box")[0].scrollHeight}, 'fast');

}

function fileType(mimeType){

  var res = mimeType.split("/");
  return res[1];
}

$('.attach').on('click', function(){
    $('#file-input').trigger('click'); 
})
