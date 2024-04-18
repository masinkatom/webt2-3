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
    const ratio = 1 * (GAME_WIDTH / 1000);
    const tickrate = 1;
    const refreshTime = 1000 / tickrate;

    canvas.width = GAME_WIDTH;
    canvas.height = GAME_HEIGHT;

    let game = new Game(GAME_WIDTH, GAME_HEIGHT);

    function animate(currentTime) {
        ctx.clearRect(0, 0, GAME_WIDTH, GAME_HEIGHT);
        game.draw(ctx);
        requestAnimationFrame(animate);
    }
    animate();

    function mouseHandler(e) {
        const rect = canvas.getBoundingClientRect();
        mouseX = e.clientX - rect.left;
        mouseY = e.clientY - rect.top;
    }

    let connected = 0;
    ws.onmessage = function (e) {
        let data = JSON.parse(e.data);
        // console.log(data);
        if (data.type === "connectedPlayer") {
            if (connected == 0) {
                game.player.uuid = data.payload;
                connected ++;
            }
            if (connected != 0) {
                game.addEnemy(data.payload);
                console.log("added enemy");
            }
        }

        if (data.type === "playerPosition") {
            data.payload.forEach(response => {
                if (response.uuid === game.player.uuid) {
                    let serverX = response.x * ratio;
                    let serverY = response.y * ratio;

                    game.player.update(serverX, serverY);
                }
                else {
                    game.enemies.forEach(enemy => {
                        if (response.uuid === enemy.uuid) {
                            let serverX = response.x * ratio;
                            let serverY = response.y * ratio;
                            enemy.update(serverX, serverY);
                        }
                    });
                }
                
                // console.log(response.uuid + ":" + response.x + " " + response.y);
            });
            console.log(data);
            
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
                payload: { x: mouseX / ratio, y: mouseY / ratio},
                sender: game.player.uuid
            };
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify(message));
            }
        }, refreshTime);
    }

    sendUserPos();

});