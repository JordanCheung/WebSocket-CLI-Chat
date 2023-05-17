(function () {
    //create a new WebSocket object.
    var msgBox = $('#message-box');
    var wsUri = "ws://localhost:8000/";
    const urlParams = new URLSearchParams(window.location.search);
    const userToken = urlParams.get('code');

    websocket = new WebSocket(wsUri);

    websocket.onopen = function (ev) { // connection is open 
        msgBox.append('<div class="system_msg" style="color:#bbbbbb">Welcome to COMP 4669 Chat!</div>'); //notify user
        console.log("websocket open");
    }
    // Message received from server
    websocket.onmessage = function (ev) {
        var response = JSON.parse(ev.data); //PHP sends Json data

        var user_message = response.message; //message text
        var user_token = response.token;
        var user_json = atob(user_token);
        var user_info = JSON.parse(user_json);
        var user_name = user_info.name;

        msgBox.append('<div><span class="user_name" style="color:blue">' + user_name + '</span> : <span class="user_message">' + user_message + '</span></div>');

        msgBox[0].scrollTop = msgBox[0].scrollHeight; //scroll message 

    };

    websocket.onerror = function (ev) { msgBox.append('<div class="system_error">Error Occurred - ' + ev.data + '</div>'); };
    websocket.onclose = function (ev) { msgBox.append('<div class="system_msg">Connection Closed</div>'); };

    //Message send button
    $('#send-message').click(function () {
        send_message();
    });

    //User hits enter key 
    $("#message").on("keydown", function (event) {
        if (event.which == 13) {
            send_message();
        }
    });

    //Send message
    function send_message() {
        var message_input = $('#message');

        if (message_input.val() == "") {
            alert("Enter Some message Please!");
            return;
        }

        var msg = {
            message: message_input.val(),
            token: userToken
        };
        websocket.send(JSON.stringify(msg));
        message_input.val('');
    }
})();