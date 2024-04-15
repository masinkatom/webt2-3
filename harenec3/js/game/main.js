import { Game } from "./Game.js";

window.addEventListener("load", () => {
    const msgBlock = document.getElementById('msg-block');
    let canvasParent = document.getElementById("gameContain");
    let canvas = document.getElementById("canvas1");
    canvas.addEventListener('mousemove', mouseHandler);

    const GAME_WIDTH = canvasParent.offsetWidth;
    const GAME_HEIGHT = GAME_WIDTH / 2;

    const ctx = canvas.getContext("2d");
    let mouseX = 0;
    let mouseY = 0;
    let serverMouseX = -1;
    let serverMouseY = -1;

    canvas.width = GAME_WIDTH;
    canvas.height = GAME_HEIGHT;

    let game = new Game(GAME_WIDTH, GAME_HEIGHT);

    function animate(currentTime) {
        ctx.clearRect(0, 0, GAME_WIDTH, GAME_HEIGHT);
        game.player.update(serverMouseX, serverMouseY);
        game.draw(ctx);
        requestAnimationFrame(animate);
    }
    animate();

    function mouseHandler(e) {
        const rect = canvas.getBoundingClientRect();
        mouseX = e.clientX - rect.left;
        mouseY = e.clientY - rect.top;
    }

    ws.onmessage = function (e) {
        let data = JSON.parse(e.data);
        console.log(data);
        if (data.uuid !== undefined) {
            game.player.uuid = data.uuid;
        }

        if (data.type === "playerPosition" && data.sender === game.player.uuid) {
            serverMouseX = data.payload.x;
            serverMouseY = data.payload.y;
            console.log(data.payload.x + " " + data.payload.y);
            
            
        }
        if (data.type === "message") {
            // console.log(data.payload);
            let newMsg = document.createElement('article');

            if (data.sender === playerName) {
                newMsg.innerHTML = `<span>@${data.sender}:</span> ${data.payload}`;
            } else {
                newMsg.innerHTML = `<span>@${data.sender}:</span> ${data.payload}`;

            }

            msgBlock.appendChild(newMsg);
        }
    };

    function sendUserPos() {
        setInterval(() => {
            message = {
                type: "playerPosition",
                payload: { x: mouseX, y: mouseY },
                sender: game.player.uuid
            };
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify(message));
            }
        }, 80);
    }

    sendUserPos();

    

});