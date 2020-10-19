var server = require('http').createServer((req, res) => {
    if (req.url === '/') {
        res.end("<h1>Hello world</h1>");
    }
});

var io = require('socket.io')(server, { origins: '*:*'});
var MySql = require('sync-mysql');
var moment = require('moment');
var session = require('express-session');
var admin = require("firebase-admin");
var path = require('path');
var config = require('./config');
var PORT = 3000 | process.env.PORT;

// get the firebase service account key
var serviceAccount = require(path.join(__dirname, "serviceAccountKey.json"));

// initialize the firebase admin account
admin.initializeApp({
    credential: admin.credential.cert(serviceAccount),
    databaseURL: "https://bisa-fr.firebaseio.com"
});


var sessionMiddleware = session({secret: 'secretkey'});

var users = {}, doctors = {};


// use  session middleware on socket, to store user session
io.use((socket,next) => {
	sessionMiddleware(socket.request, socket.request.res, next)
});


// on socket connection
io.on('connection', (socket) => {
    io.emit('connectionSuccessful', 'You are successfully connected to the bisa chat engine');
    var userSession = socket.request.session;

    // register user
    socket.on('registerUserSession', (userId) => {
        userSession.userId = userId;
        users[userId] = socket;
    });

    // register doctor
    socket.on('registerUserSession', (doctorId) => {
        userSession.userId = doctorId;
        doctors[doctorId] = socket;
    });

    //socket events
    socket.on('getUserQuestions', (userId) => {
        let data = [];

        var connection = new MySql({
            host: config.SERVER_NAME,
            user: config.USER_NAME,
            database: config.DATABASE_NAME,
            password: config.PASSWORD,
        });

        let rows = connection.query(`SELECT * FROM questions WHERE patient_id = ${userId}`);

        for (item of rows) {
            let tempObj = {};
            tempObj.question_id = item.question_id;
            tempObj.patient_id = item.patient_id;
            tempObj.question_code = item.question_code;
            tempObj.question_cat_id = item.question_cat_id;
            tempObj.question_closed = item.question_closed;
            tempObj.question_answered = item.question_answered;
            tempObj.question_content = item.question_content;
            tempObj.question_code = item.question_code;
            tempObj.question_media_url = item.question_media_url;
            tempObj.created_at = item.created_at;

            var categoryId = item.question_cat_id;
            var categoryNameQuery = `SELECT category_name FROM question_categories WHERE category_id = ${item.question_cat_id}`;

            //get the category name of a question
            var questionCategory  = connection.query(categoryNameQuery);
            tempObj.question_category  = questionCategory[0].category_name;

            if (item.question_answered == 'no') {
                tempObj.question_threads = 1;
                tempObj.response_doctor = 'n/a';
            } else {
                var questionThreadsQuery = connection.query(`SELECT * FROM question_responses WHERE ques_id = ${item.question_id}`);
                tempObj.question_threads = questionThreadsQuery.length + 1;

                var doctorAnsweredQuery = connection.query(`SELECT * FROM question_responses WHERE ques_id = ${item.question_id} AND responder_type = 'doctor' LIMIT 1`);
                if (doctorAnsweredQuery.length > 0) {
                    var doctorDetailsQuery =  connection.query(`SELECT * FROM doctors WHERE doctor_id = ${doctorAnsweredQuery[0].responder_id} LIMIT 1`);
                    tempObj.response_doctor = `Dr. ${doctorDetailsQuery[0].first_name} ${doctorDetailsQuery[0].last_name}`
                } else {
                    tempObj.response_doctor = 'n/a';
                }
            }

            data.push(tempObj);
        }

        io.emit('updateUserQuestions', data)
    })


    socket.on('getUserQuestionDetails',(userId, questionId) => {
        let data = {},
            questionsArray = [];

        var connection = new MySql({
            host: config.SERVER_NAME,
            user: config.USER_NAME,
            database: config.DATABASE_NAME,
            password: config.PASSWORD,
        });

        var userQuestions = connection.query(`SELECT * FROM questions WHERE patient_id = ${userId} AND question_id = ${questionId} LIMIT 1`);
        if (userQuestions.length > 0) {
            var tempQuestionObj = {};

            data.question_code = userQuestions[0].question_code;
            data.question_id = userQuestions[0].question_id;
            data.question_closed = userQuestions[0].question_closed;
            data.question_closed = userQuestions[0].question_closed;
            data.patient_id = userQuestions[0].patient_id;

            data.question_category = connection.query(`SELECT * FROM question_categories WHERE category_id = ${userQuestions[0].question_cat_id}`)[0].category_name;

            tempQuestionObj.question_content = userQuestions[0].question_content;
            tempQuestionObj.question_media_url = userQuestions[0].question_media_url;
            tempQuestionObj.created_at = userQuestions[0].created_at;

            tempQuestionObj.creator = connection.query(`SELECT * FROM users WHERE user_id = ${userId}`)[0].username;

            questionsArray.push(tempQuestionObj);

            var questionResponses = connection.query(`SELECT * FROM question_responses WHERE ques_id = ${questionId}`);
            if (questionResponses.length > 0) {
                for (response of questionResponses) {
                    tempQuestionObj.question_content = response.question_response_content;
                    tempQuestionObj.question_media_url = response.question_response_media_url;
                    tempQuestionObj.created_at = response.created_at;

                    if (response.responder_type == 'doctor') {
                        var doctorDetails = connection.query(`SELECT * FROM doctors WHERE doctor_id = ${response.responder_id}`);
                        var doctorName = "Dr. " + doctorDetails[0].first_name + " " + doctorDetails[0].last_name;
                        tempQuestionObj.creator = doctorName;
                    } else {
                        tempQuestionObj.creator = connection.query(`SELECT * FROM users WHERE user_id = ${userId}`)[0].username;
                    }
                    questionsArray.push(tempQuestionObj);
                }
            }

            data.question_threads = questionsArray;
        }

        io.emit('updateUserQuestionDetails', data)
    })

    socket.on('sendUserChat', (data) => {
        var connection = new MySql({
            host: config.SERVER_NAME,
            user: config.USER_NAME,
            database: config.DATABASE_NAME,
            password: config.PASSWORD,
        });

        var dateTime = moment().format('YYYY-MM-DD HH:mm:ss');
        connection.query(`INSERT INTO question_responses(responder_id,responder_type,ques_id, question_response_content,question_response_media_url, created_at, updated_at) VALUES(${data.userId}, '${data.responderType}', ${data.questionId}, '${data.questionContent}',' ${data.questionMedia}', '${dateTime}', '${dateTime}')`);

        var getUsernameQuery = connection.query(`SELECT username FROM users WHERE user_id = ${data.userId}`);

        data.username = getUsernameQuery[0].username;
        data.createdAt = dateTime;
        io.emit('receiveUserChat', data);
    })

    socket.on('notifyDoctorReply', (data) => {
        console.log('from doc', data);
        io.emit('recieveDoctorChat', data);
    })

    //on disconnect
    socket.on('disconnect', () => {
        delete users[userSession.userId];
        delete doctors[userSession.userId];
        io.emit('userDisconnected', {userId: userSession.userId});
        userSession.userId = null;
    });
});

server.listen(PORT, () => {
    console.log(`server listening on port ${PORT}...`)
});
