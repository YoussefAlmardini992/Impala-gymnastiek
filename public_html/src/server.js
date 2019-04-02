const socket = require('socket.io'),
      express = require('express'),
      https = require('https'),
      http = require('http'),
      logger = require('winston'),
      mysql = require('mysql');

let connection = mysql.createConnection({
  host: 'localhost',
  user: 'admin',
  password: 'admin',
  database: 'rocole_db10'
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
  const users = [];

  io.sockets.on('connection', function (socket) {

    //Socket Actions
    //ON SELECT GROUP
    socket.on('select_group', function (Group_ID) {

      connection.query('SELECT * FROM deelnemers WHERE groep_ID = "' + Group_ID + '"', function (error, results, fields) {
        if (error) throw error;
        socket.emit('selected_group', results);
      });
    });
    
    //ON SELECT JURY
    socket.on('Login_value', function (value) {
      socket.broadcast.emit('get_user',value);

    });
  });
}

emitConnection(http_server);