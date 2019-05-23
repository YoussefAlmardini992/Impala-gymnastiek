const connectionData = require('./sqlConnection.js');

const socket = require('socket.io'),
    express = require('express'),
    https = require('https'),
    http = require('http'),
    logger = require('winston'),
    mysql = require('mysql');

let connection = mysql.createConnection(connectionData);
var users = [];
var lastUser;

connection.connect(function (err) {
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
        socket.on('new-screen', function () {
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

        socket.on('getCardData', function (card) {
            // LAST QUERY FOR ALL DATA
            // try{
            //     connection.query('SELECT deelnemers.deelnemer_ID, wedstrijden.wedstrijd_ID, subonderdeel.subonderdeel_id,subonderdeel.onderdeel_id ' +
            //         'FROM wedstrijden ' +
            //         'JOIN groepen ON wedstrijden.groep_ID = groepen.groep_ID ' +
            //         'JOIN deelnemers ON deelnemers.groep_ID = groepen.groep_ID ' +
            //         'CROSS JOIN subonderdeel ' +
            //         'WHERE deelnemers.nummer=' + card.Nummer + ' ' +
            //         'AND subonderdeel.subonderdeel = ' + '"' + card.Onderdeel + '"', function (error, results, fields) {
            //         if (error) throw error;
            //         // INSERT QUERY
            //         connection.query("INSERT INTO `scores` (`deelnemer_ID`, `wedstrijd_ID`, `onderdeel_id`, `subonderdeel_id`, `D_score`, `E_score`, `N_score`)" +
            //             "VALUES (" + results[0].deelnemer_ID + ", " + results[0].wedstrijd_ID + ", " + results[0].onderdeel_id + ", " + results[0].subonderdeel_id + ", " + card.D + ", " + card.E + ", " + card.N + ")", function (error, results, fields) {
            //             if (error) throw error;
            //             console.log('insert is done');
            //         });
            //     });
            // }catch (e) {
            //     console.error(e.message);
            // }finally {
            //
            // }

        });


        //ON SELECT USER
        socket.on('LoginValue', function (value) {
            let notExistIndex = 0;
            let pushed;
            if (users.length < 1) {
                users.push(value);
                pushed = true;
            } else {
                for (var user in users) {
                    if (users[user].name === value.name) {
                        notExistIndex++;
                    }
                }
                if (notExistIndex === 0) {
                    users.push(value);
                    pushed = true;
                }
            }
            pushed ? lastUser = users[users.length - 1] : null;
               socket.broadcast.emit("all_users" , users);
        });


        socket.on('requestUser', function (user) {
            let userExist = false;
            try {
                for (i = 0; i < users.length; i++) {
                    if (users[i].name === user.name) {
                        userExist = true;
                        break;
                    }
                    else {
                        userExist = false;
                    }
                }
            } catch (e) {
                console.error('user is Undefiend ');
            }
            finally {

                socket.emit('sendUrl', {
                    userExist: userExist,
                    user: lastUser
                });
            }
        });

        socket.on('logOut', function (USER) {
            users.forEach(function (user) {
                if (user.name === USER) {
                    var index = users.indexOf(user);
                    if (index > -1) {
                        users.splice(index, 1);
                    }
                    lastUser = null;
                }
            });
        });

        //ON START MATCH
        // 3. Make sure all the screens receive the group. There can be multiple connections. - Jarrin
        socket.on('start_match', function (group) {
            // Loop al screen connection and emit the event.
            screenConnections.forEach((connection) => {
                connection.emit('get_group', group);
            });
        });

        //ON SELECT DEELNEMER
        socket.on('set_current_deelnemer', function (deelnemer) {
            socket.broadcast.emit('get_current_deelnemer', deelnemer);
        });

        //ON SEND SCORES
        socket.on('set_deelnemer_score', function (scores) {
            socket.broadcast.emit('get_deelnemer_score', scores);
        });

        //ON RECEIVE DONE DEELNAMER
        socket.on('setDoneTurner', function (deelnemer) {

            connection.query('INSERT INTO scores () VALUES ();', function (error, results, fields) {
                if (error) throw error;

            });

            console.log(deelnemer);
        });

        //////// EXTRA CODE VAN THIJMEN LOCAAL
        socket.on('send_Turner_score', function (scores) {
            socket.broadcast.emit('send_Turner_score_to_secretariaat', scores);
        });

        //ON RECIEVE CARD
        socket.on('send_Turner_card', function (card) {
            console.log(card);
            socket.broadcast.emit('get_Turner_card', card);
        });

    });


}

emitConnection(http_server);