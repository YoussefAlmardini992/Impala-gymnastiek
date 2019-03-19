const socket = require('socket.io'),
      express = require('express'),
      https = require('https'),
      http = require('http'),
      logger = require('winston');

const app = express();
const http_server = http.createServer(app).listen(3001);

logger.info('Socket connected...');

function emitConnection(SERVER) {

  const io = socket.listen(SERVER);

  io.sockets.on('connection',function (socket) {

    //Socket Orders
    //ON SELECT GROUP
    socket.on('select_group',function (groupName) {
      io.emit('select_group',groupName)
    })

  })
}

emitConnection(http_server);