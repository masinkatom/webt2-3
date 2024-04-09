const nameSpan = document.getElementById('alias');
const msgBlock = document.getElementById('msg-block');
const msgText = document.getElementById('msg-text');
msgText.addEventListener('input', (e) => {
    if (e.target.value !== "") {
        msgBtn.removeAttribute('disabled');
    } else {
        msgBtn.setAttribute('disabled', 'true');
    }
});

const msgBtn = document.getElementById('send');
username = "UUID";

let ws = new WebSocket("wss://node10.webte.fei.stuba.sk/wss");

// code to keep the connection alive
const heartbeatInterval = setInterval(() => {
    if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({ type: 'ping' }));
    }
}, 20000);

ws.onopen = function(e) {
    
};

ws.onmessage = function(e) {
    // console.log(e.data)
    console.log(e);
    data = JSON.parse(e.data);
    if (data.uuid) {
        // nameSpan.innerHTML = `@${data.uuid}`;
        username = nameSpan.innerHTML;
    }
    if (data.type === "message") {
        // console.log(data.payload);
        newMsg = document.createElement('article');
        
        if (data.sender === username) {
            newMsg.innerHTML = `<span>@${data.sender}:</span> ${data.payload}`;
        } else {
            newMsg.innerHTML = `<span>@${data.sender}:</span> ${data.payload}`;

        }

        msgBlock.appendChild(newMsg);
    }
};

ws.onclose = function(e) {
    clearInterval(heartbeatInterval);  
}


const sendMessage = () => {
    if (msgText.value !== "") {
        message = {
            type: "message",
            payload: msgText.value,
            sender: username
        }
        ws.send(JSON.stringify(message));

        msgText.value = "";
        msgBtn.setAttribute('disabled', 'true');
    }
}