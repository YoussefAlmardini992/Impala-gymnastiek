const socket = require('socket.io'),
      express = require('express'),
      https = require('https'),
      http = require('http'),
      logger = require('winston'),
      mysql = require('mysql');

let connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'rocle_db10'
});

connection.connect(function(err) {
  if (err) {
    return console.error('error: ' + err.message);
  }

  console.log('Connected to the MySQL server.');
});


const app = express();
const http_server = http.createServer(app).listen(3000);

logger.info('Socket connected...');

function emitConnection(SERVER) {

  const io = socket.listen(SERVER);

  io.sockets.on('connection',function (socket) {

    //Socket Orders
    //ON SELECT GROUP
    socket.on('select_group',function (groupName) {

      console.log(groupName);

      connection.query('SELECT * FROM deelnemers', function (error, results, fields) {
        if (error) throw error;
        console.log('The solution is: ', results[0]);
        socket.emit('selected_group',results[0])
      });
    })
  })
}

emitConnection(http_server);