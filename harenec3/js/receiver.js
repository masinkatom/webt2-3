
const nameSpan = document.getElementById('alias');
var ws = new WebSocket("wss://node10.webte.fei.stuba.sk/wss");

// window.addEventListener("DOMContentLoaded", async () => {
    const playerName = getNickName();
    const btnSend = document.getElementById('btn-send');
    const msgText = document.getElementById('msg-text');

    btnSend.addEventListener("click", sendMessage);

    msgText.addEventListener('input', (e) => {
        if (e.target.value !== "") {
            btnSend.removeAttribute('disabled');
        } else {
            btnSend.setAttribute('disabled', 'true');
        }
    });


    // code to keep the connection alive
    const heartbeatInterval = setInterval(() => {
        if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({ type: 'ping' }));
        }
    }, 20000);

    ws.onopen = async function (e) {
        console.log("connected");
        sendUserConnected(playerName + ' has connected');
    };


    // RECEIVE MESSAGE FROM SERVER
    ws.onmessage = function (e) {
        data = JSON.parse(e.data);
        if (data.uuid) {
            // nameSpan.innerHTML = `@${data.uuid}`;
            username = playerName;
        }
    };

    ws.onclose = function (e) {
        clearInterval(heartbeatInterval);
    }


    function sendMessage() {
        if (msgText.value !== "") {
            message = {
                type: "message",
                payload: msgText.value,
                sender: playerName
            }
            ws.send(JSON.stringify(message));

            msgText.value = "";
            btnSend.setAttribute('disabled', 'true');
        }
    }

    async function sendUserConnected(info) {
        message = {
            type: "connectedUser",
            payload: info,
            sender: playerName
        };
        console.log("info:" + info);
        ws.send(JSON.stringify(message));
    }

    async function getNickName() {
        let uname = "tonko";
        if (localStorage.getItem("nick") !== null) {
            uname = localStorage.getItem("nick");
        }
        return uname;
    }
// });

