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

  io.sockets.on('connection', function (socket) {

    //Socket Actions
    //ON SELECT GROUP
    socket.on('select_group', function (Group_ID) {


      connection.query('SELECT * FROM deelnemers INNER JOIN  groepen ON deelnemers.groep_ID = groepen.ID WHERE groep_ID="' + Group_ID + '"', function (error, results, fields) {
        if (error) throw error;
        socket.emit('selected_group', results);
      });

    });
    
    //ON SELECT USER
    socket.on('Login_value', function (value) {
      socket.broadcast.emit('get_user',value);
    });

    //ON START MATCH
    socket.on('start_match',function (group) {
      console.log(group);
      socket.emit('get_group',group);
    });
  });
}

emitConnection(http_server);