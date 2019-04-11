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

const screenConnections = [];

function emitConnection(SERVER) {

  const io = socket.listen(SERVER);

  io.sockets.on('connection', function (socket) {


    // 2. Keep track of new screens so we can send messages to it. - Jarrin
    socket.on('new-screen', function() {
      console.log('new-screen');
      screenConnections.push(socket);
    });



    //Socket Actions
    //ON SELECT GROUP
    socket.on('select_group', function (Group_ID) {

      console.log(Group_ID);

      connection.query('SELECT * FROM deelnemers INNER JOIN groepen ON deelnemers.groep_ID = groepen.groep_ID WHERE groepen.groep_ID="' + Group_ID + '"', function (error, results, fields) {
        if (error) throw error;
        socket.emit('selected_group', results);
        console.log(results);
      });

    });

    //ON SELECT USER
    socket.on('Login_value', function (value) {
      socket.broadcast.emit('get_user',value);
    });

    //ON START MATCH
    // 3. Make sure all the screens receive the group. There can be multiple connections. - Jarrin
    socket.on('start_match',function (group) {
      // Loop al screen connection and emit the event.
      screenConnections.forEach((connection) => {
        connection.emit('get_group', group);
      });
    });

    //ON SELECT DEELNEMER
    socket.on('set_current_deelnemer',function (deelnemer) {
      socket.broadcast.emit('get_current_deelnemer',deelnemer);
    });

    //ON SEND SCORES
    socket.on('set_deelnemer_score',function (scores) {
      socket.broadcast.emit('get_deelnemer_score',scores);
    });

    //ON RECEIVE DONE DEELNAMER
    socket.on('setDoneTurner',function (deelnemer) {

      connection.query('INSERT INTO scores () VALUES ();', function (error, results, fields) {
        if (error) throw error;

      });

      console.log(deelnemer);
    });

    //////// EXTRA CODE VAN THIJMEN LOCAAL

    socket.on('send_Turner_score',function (scores) {
      socket.broadcast.emit('send_Turner_score_to_secretariaat',scores);
    });

  });
}

emitConnection(http_server);