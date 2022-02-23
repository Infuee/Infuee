var axios = require('axios');
var fs = require('fs');
var ENV = 'stage';
 // var ENV='local';
//var ENV='prod';
var privateKey,port, certiifcate, host_server = 'http://socket.infuee.com/';

if( ENV == 'stage' ){
  host_server1 = 'https://infuee.com/';
  host_server = 'https://infuee.com/' ;
  port = 3004;
  // privateKey = fs.readFileSync('privkey.pem').toString();
  // certiifcate = fs.readFileSync('fullchain.pem').toString();
}else{
  host_server1 = 'http://socket.local';
  port = 3000;
  privateKey = fs.readFileSync('privkey.pem').toString();
  certiifcate = fs.readFileSync('fullchain.pem').toString();
}
var https = require('https');
var app = require('express')();

var options = {
  key: privateKey,
  cert: certiifcate
};
var server = https.createServer(options, app);
 
var io = require('socket.io')(server, {
  cors: {
    origin: 'https://infuee.com',
    methods: ["GET", "POST"]
  }
});
var SocketIOFile = require('socket.io-file');

var instance = axios.create({
  httpsAgent: new https.Agent({
    rejectUnauthorized: false
  })
});
var fileDir = 'public/uploads/chat';
var sockets = [];
var fileChunks = [];

io.on('connection', function (socket) {
  var id = socket.id;
  //console.log("new client connected", id);
  var uploader = new SocketIOFile(socket, {
    uploadDir: fileDir,              // simple directory
    accepts: [
      'application/wps-office.doc',
      'application/msword',
      'application/pdf',
      'application/x-sql',
      'application/sql',
      'application/xlsx',
      'text/plain',
      'image/png',
      'image/jpg',
      'image/jpeg',
      'image/gif',
      'image/bmp',
      'audio/mpeg',
      'audio/mp3',
      'audio/x-wav',
      'video/mpeg',
      'video/mp4',
    ],   // chrome and some of browsers checking mp3 as 'audio/mp3', not 'audio/mpeg'
    maxFileSize: 41943040,             // 4 MB. default is undefined(no limit)
    chunkSize: 10240,             // default is 10240(1KB)
    transmissionDelay: 0,           // delay of each transmission, higher value saves more cpu resources, lower upload speed. default is 0(no delay)
    overwrite: true               // overwrite file if exists, default is true.
  });
  uploader.mode = "0777";

  uploader.on('start', (fileInfo) => {
    //console.log('Start uploading');
    //console.log(fileInfo);
  });
  uploader.on('stream', (fileInfo) => {
    //console.log(`${fileInfo.wrote} / ${fileInfo.size} byte(s)`);
  });
  uploader.on('complete', (fileInfo) => {
    //console.log('Upload Completesss.');
    //console.log(fileInfo);
    fs.chmod(fileInfo.uploadDir, '0777', function () {
      //console.log('file saved on server...',fileInfo);
      /*instance.post(host_server + 'api/savemessage', fileInfo)
        .then(res => {
          console.log('res88', res.data);
          var res_ = fileInfo.data;
          out = io.sockets.in(res_.receiver_id + "_" + res_.sender_id).emit('chat-message', res.data);
             io.sockets.in(res_.sender_id+"_"+res_.receiver_id).emit('chat-message', res.data);
          
          if (res.data.is_first) {
            var connection = '';
            io.sockets.in(res_.receiver_id).emit('check-connection', res.data);
          }
        });*/
      instance.post(host_server + 'api/savemessage', fileInfo)
      .then(res => {
        //console.log('chat-message zzzz', res.data);
        var res_ = fileInfo.data;
          io.sockets.in(res_.receiver_id).emit('chat-message', res.data);
          io.sockets.in(res_.receiver_id).emit('alert-message', res.data);
      });

    });

  });
  uploader.on('error', (err) => {
    console.log('Error!', err);
  });
  uploader.on('abort', (fileInfo) => {
    console.log('Aborted: ', fileInfo);
  });


  socket.on('chat-message', function (data) {

    var res_ = data;

    if (typeof res_ == 'string') {
      res_ = JSON.parse(res_);
    }
    //console.log('chat-message', JSON.stringify(res_), host_server + 'api/savemessage' );

    instance.post(host_server + 'api/savemessage', res_)
      .then(res => {
        //console.log('chat-message', res.data);
    //     var out = 0;
    //     if (typeof res_.receiver_id == 'undefined') {
    //       if (typeof res_.department_id !== 'undefined') {
    //         var employee = typeof res_.employee_id == 'undefined' ? res_.sender_id : res_.employee_id;
    //         out = io.sockets.in(res_.department_id + employee).emit('chat-message', res.data);
    //       } else {
    //         out = io.sockets.in(res_.group_id).emit('chat-message', res.data);
    //       }
    //     } else {
          io.sockets.in(res_.receiver_id).emit('chat-message', res.data);
          io.sockets.in(res_.receiver_id).emit('alert-message', res.data);
        // }
        // if (res.data.is_first) {
        //   var connection = '';
        //   if (typeof res_.receiver_id !== 'undefined') {
        //     io.sockets.in(res_.receiver_id).emit('check-connection', res.data);
        //   }
        //   if (typeof res_.department_id !== 'undefined') {
        //     res.data.department_users.forEach(function (user) {
        //       io.sockets.in(user).emit('check-connection', res.data);
        //     })
        //   }
        //   if (typeof res_.group_id !== 'undefined') {
        //     res.data.group_users.forEach(function (user) {
        //       io.sockets.in(user).emit('check-connection', res.data);
        //     })
        //   }
        // }
      });
  });
  
  socket.on('file_share', function (data) {
    //console.log('file_share', data);

    fs.writeFile(fileDir + '/' + data[0].name, data[0], {
      flag: "w"
    }, function (err) {
      if (err)
        return console.log(err);
      //console.log("The file was saved!");
    });

  });

  socket.on('file-emitter', function (data) {
    //console.log('file-emitter', data);
    var res = data;
    if (typeof res == 'string') {
      res = JSON.parse(res);
    }
    var fileInfo = {};
    var fileName = fileDir + '/' + res.file_name;
    fileInfo.uploadDir = fileName;
    fileInfo.name = res.file_name;
    fs.writeFile(fileName, res.message, {
      flag: "w"
    }, function (err) {
      fs.chmod(fileName, '0777', function () {
        instance.post(host_server + '/api/saveimage', fileInfo)
          .then(res => {
            return res;
          })
          .then(res => {
            //console.log('res', res.data);
            return false;
            var res_ = fileInfo.data;
            if (typeof res_.receiver_id == 'undefined') {
              if (typeof res_.department_id !== 'undefined') {
                var employee = typeof res_.employee_id == 'undefined' ? res_.sender_id : res_.employee_id;
                out = io.sockets.in(res_.department_id + employee).emit('chat-message', res.data);
              } else {
                out = io.sockets.in(res_.group_id).emit('chat-message', res.data);
              }
            } else {
              out = io.sockets.in(res_.receiver_id + "_" + res_.sender_id).emit('chat-message', res.data);
              io.sockets.in(res_.sender_id + "_" + res_.receiver_id).emit('chat-message', res.data);
            }
            if (res.data.is_first) {
              var connection = '';
              if (typeof res_.receiver_id !== 'undefined') {
                io.sockets.in(res_.receiver_id).emit('check-connection', res.data);
              }
              if (typeof res_.department_id !== 'undefined') {
                res.data.department_users.forEach(function (user) {
                  io.sockets.in(user).emit('check-connection', res.data);
                })
              }
              if (typeof res_.group_id !== 'undefined') {
                res.data.group_users.forEach(function (user) {
                  io.sockets.in(user).emit('check-connection', res.data);
                })
              }
            }

          });
      });
    });

  });

  


  socket.on('user-join', function (data) {
    var res = data;
    //console.log("qqq",res);
    io.emit('user_active_list', res);
    if (typeof res == 'staff') {
      res = JSON.parse(res);
    }
    sockets[id] = res.id;

    if (typeof res.receiver_id == 'undefined') {
      if (typeof res.department_id !== 'undefined') {
        var employee = typeof res.employee_id == 'undefined' ? res.id : res.employee_id;
        socket.join(res.department_id + employee);
      } else {
        socket.join(res.group_id);
      }
    } else {
       //console.log("heyy", res);
      socket.join(res.receiver_id);
    }

    socket.join(res.id);
  });

  socket.on('user-unjoin', function (data) {
    var res = data;
    if (typeof res == 'string') {
      res = JSON.parse(res);
    }
    //console.log('user-unjoin', res);
    if (typeof res.receiver_id == 'undefined') {
      if (typeof res.department_id !== 'undefined') {
        var employee = typeof res.employee_id == 'undefined' ? res.id : res.employee_id;
        socket.leave(res.department_id + employee);
      } else {
        socket.leave(res.group_id);
      }
    } else {
      socket.leave(res.id + '_' + res.receiver_id);
    }
    socket.leave(res.id);
  });

  socket.on('disconnect', function (data) {

    var data = { id: sockets[id], status: 'inactive' };
    //console.log('disconnect', data);
    instance.post(host_server + 'api/useractives', data)
      .then(res => {
        //console.log("disconnect",res)
        //return res;
      })
      /*.then(res => {
        io.emit('user_active', { id: sockets[id], status: 'inactive' });
      });*/

  });

  socket.on('user-active', function (data) {

    var res_ = data;
    //console.log("hinw",res_);
    if (typeof res_ == 'string') {
      res_ = JSON.parse(res_);
    }

    sockets[id] = res_.id;
    if (typeof res_.status == 'undefined') {
      res_.status = 'active';
    }
    socket.join(res_.id);
    //console.log('user active again', res_);

    


    instance.post(host_server + 'api/useractives', res_)
      .then(res => {
        //console.log('active userssss',res_);
        io.emit('user_joined', res_);
        io.emit('user_active', { id: res_.id, status: 'active' });
      });
      /*.then(res => {
        console.log("aaaaaaaaaaaaa",res_);
        io.emit('user_joined', res_);
        io.emit('user_active', { id: res_.id, status: 'active' });
      });*/
  });

  socket.on('user-in-active', function (data) {

    var res_ = data;
    if (typeof res_ == 'string') {
      res_ = JSON.parse(res_);
    }
    //console.log("uuuuuuuuuu",res_);
    sockets[id] = res_.id;
    if (typeof res_.status == 'undefined') {
      res_.status = 'inactive';
    }

    instance.post(host_server + '/api/useractive', res_)
      .then(res => {
        return res;
      })
      .then(res => {
        io.emit('user-unjoin', { user_id: res_.id, status: new Date() });
      });

  });

  socket.on('typing_start', function (data) {
    var res = data;
    if (typeof res == 'string') {
      res = JSON.parse(res);
    }
    //console.log('typing_start_recieve', res);
    io.emit('typing_status', { user_id: res.sender_id,connection_id:res.connection_id, status: 'Typing....' })
    out = io.sockets.in(res.connection_id + '_' + res.sender_id).emit('typing_status', { user_id: res.sender_id, status: 'Typing....' });
  });

 /* socket.on('typing_end', function (data) {
    var res = data;
    if (typeof res == 'string') {
      res = JSON.parse(res);
    }
    console.log('typing_end', res);
    io.emit('typing_status', { user_id: res.sender_id,connection_id:res.connection_id, status: '' })
    io.sockets.in(res.connection_id + '_' + res.sender_id).emit('typing_status', { user_id: res.sender_id, status: 'Online' });
  });*/

  socket.on('typing', function (data) {
    var res = data;
    if (typeof res == 'string') {
      res = JSON.parse(res);
    }
    //console.log('typing', res);
    io.emit('typing_status', { user_id: res.user_id, status: res.status });
  });

  socket.on('chat_on_msg_status', function (data,myParticipant_id){
    var chatDataStatus = { myParticipant_id : myParticipant_id, chatDataStatus : data };
    //console.log("check entire upadatr",data)
    instance.post(host_server + 'api/chatonstatus', chatDataStatus)
      .then(res => {
       // console.log('chat on status',res.data);
        
      });
  })

  socket.on('uploadFileStart', function (data) {
    var res = data;
    if (typeof res == 'string') {
      res = JSON.parse(res);
    }
    //console.log('uploadFileStart', res);
    fileChunks[res.sender_id + '_' + res.receiver_id] = res.message;
    io.emit('uploadFileStartReceived', res);
  });

  socket.on('uploadFileChuncks', function (data) {
    var res = data;
    if (typeof res == 'string') {
      res = JSON.parse(res);
    }
    //console.log('uploadFileChuncks', res, (fileChunks[res.sender_id + '_' + res.receiver_id]).length);
    fileChunks[res.sender_id + '_' + res.receiver_id] = (fileChunks[res.sender_id + '_' + res.receiver_id]).concat(res.message);
    io.emit('uploadFileChuncksReceived', res);
  });

  socket.on('uploadFileComplete', function (data) {
    var res = data;
    if (typeof res == 'string') {
      res = JSON.parse(res);
    }
    if (typeof res.file_name == 'undefined') {
      return false;
    }
    var fileComplete = fileChunks[res.sender_id + '_' + res.receiver_id];
    //console.log('uploadFileComplete', res);
    var myBuffer = Buffer.alloc(fileComplete.length);
    for (var i = 0; i < fileComplete.length; i++) {
      myBuffer[i] = fileComplete[i];
    }
    fs.writeFile(fileDir + '/' + res.file_name, myBuffer, {
      flag: "w"
    }, function (err) {
      if (err)
        return console.log(err);

      fs.chmod(fileDir + '/' + res.file_name, '0777', function () {

        var fileInfo = {};
        var fileName = fileDir + '/' + res.file_name;
        fileInfo.uploadDir = fileName;
        fileInfo.name = res.file_name;
        fileInfo.data = res;
        fileInfo.mime = res.file_mime;
        instance.post(host_server + '/api/saveimage', fileInfo)
          .then(res => {
            return res;
          })
          .then(res => {
            //console.log('res', res.data);
            return false;
            var res_ = fileInfo.data;
            if (typeof res_.receiver_id == 'undefined') {
              if (typeof res_.department_id !== 'undefined') {
                var employee = typeof res_.employee_id == 'undefined' ? res_.sender_id : res_.employee_id;
                out = io.sockets.in(res_.department_id + employee).emit('file-sent', res.data);
              } else {
                out = io.sockets.in(res_.group_id).emit('file-sent', res.data);
              }
            } else {
              out = io.sockets.in(res_.receiver_id + "_" + res_.sender_id).emit('file-sent', res.data);
              io.sockets.in(res_.sender_id + "_" + res_.receiver_id).emit('file-sent', res.data);
            }
          });
      });
    });
    delete fileComplete[res.sender_id + '_' + res.receiver_id];
  });

  socket.on('mark_seen', function (data) {
    var res_ = data;
    if (typeof res_ == 'string') {
      res_ = JSON.parse(res_);
    }
    //console.log('mark_seen', res_);
    instance.post(host_server + 'api/markseenmsgs', res_)
      .then(res => {
        //console.log('chat-message zzzz', res.data);
        io.emit('count_message',res.data);
        /*var res_ = fileInfo.data;
          io.sockets.in(res_.receiver_id).emit('chat-message', res.data);
          io.sockets.in(res_.receiver_id).emit('alert-message', res.data);*/
      });
     /* instance.post(host_server + 'api/markseen', res_)
        .then(res => {
          console.log('mark_seen Reponse From API', res.data);
          return res;
        })
        .then(res => {
          io.emit('mark_seen', res.data);
      });*/

  });

  socket.on('readmarkmsg', function (data,userParticapant) {
    //console.log('check unread messages', data);
    var msgdata = data;
    instance.post(host_server + 'api/markreadmsgs', msgdata)
    .then(res => {
        //console.log('readmessages', res.data);
      });

  })

  socket.on('message_delete', function (data) {
    var res_ = data;
    if (typeof res_ == 'string') {
      res_ = JSON.parse(res_);
    }
    //console.log('message_delete', res_);
    instance.post(host_server + '/api/chat/delete/message', res_)
      .then(res => {
        //console.log(res.data)
        return res;
      })
      .then(res => {
        var msg = res.data.msg;
        if(typeof msg.group_id != 'undefined'){
          io.sockets.in(msg.group_id).emit('message_deleted', res.data);
        }else if(typeof msg.department_id != 'undefined'){
          io.sockets.in(msg.department_id + msg.employee_id ).emit('message_deleted', res.data);
        }else{
          io.sockets.in(msg.receiver_id + "_" + msg.sender_id).emit('message_deleted', res.data);
          io.sockets.in(msg.sender_id + "_" + msg.receiver_id).emit('message_deleted', res.data);
        }
    });


  });

  socket.on('group_leave', function (data) {
    var res_ = data;
    if (typeof res_ == 'string') {
      res_ = JSON.parse(res_);
    }
    //console.log('group_leave', res_);
    instance.post(host_server + '/api/chat/leaveGroup', res_)
      .then(res => {
        //console.log(res.data)
        return res;
      })
      .then(res => {
        var msg = res.data.msg;
        io.sockets.in(res_.group_id).emit('group_left', res.data);
    });
  });

});

io.listen(port, {
  key: privateKey,
  cert: certiifcate
}, function () {

  console.log('socket.io server listen at '+port);

});
