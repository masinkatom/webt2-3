import { Game } from "./Game.js";
import { Player } from "./Player.js";

// window.addEventListener("load", () => {
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
    const tickrate = 32;
    const refreshTime = 1000 / tickrate;

    canvas.width = GAME_WIDTH;
    canvas.height = GAME_HEIGHT;

    let game = new Game(GAME_WIDTH, GAME_HEIGHT);

    function animate(currentTime) {
        ctx.clearRect(0, 0, GAME_WIDTH, GAME_HEIGHT);

        if (game.player !== null) {
            game.draw(ctx);
        }

        requestAnimationFrame(animate);
    }

    
    
    function mouseHandler(e) {
        const rect = canvas.getBoundingClientRect();
        mouseX = e.clientX - rect.left;
        mouseY = e.clientY - rect.top;
    }

    ws.onmessage = function (e) {
        let data = JSON.parse(e.data);
        // console.log(data);
        if (data.type === "connectedPlayer") {
            if (game.player === null) {
                game.player = new Player(game, data.payload);
            }
            else {
                game.addEnemy(data.payload);
            }
        }

        if (data.type === "disconnectedPlayer") {
            game.removeEnemy(data.payload);
        }

        if (data.type === "enemiesOnServer") {
            data.payload.forEach(response => {
                game.addEnemy(response.uuid);
            });
        }

        if (data.type === "playerPosition") {
            data.payload.forEach(response => {
                if (game.player !== null && response.uuid === game.player.uuid) {
                    let serverX = response.x * ratio;
                    let serverY = response.y * ratio;

                    game.player.update(serverX, serverY);
                    response.lines.forEach(lineResponse => {
                        let lineX1 = lineResponse.x1 * ratio;
                        let lineY1 = lineResponse.y1 * ratio;
                        let lineX2 = lineResponse.x2 * ratio;
                        let lineY2 = lineResponse.y2 * ratio;
                        game.player.addLine(lineX1, lineY1, lineX2, lineY2);
                    });
                }
                else {
                    game.enemies.forEach(enemy => {
                        if (response.uuid === enemy.uuid) {
                            let serverX = response.x * ratio;
                            let serverY = response.y * ratio;
                            enemy.update(serverX, serverY);
                        }
                        console.log(enemy);
                        console.log(enemy.x + " " + enemy.y);
                    });
                }
                
                // console.log(response.uuid + ":" + response.x + " " + response.y);
            });
            
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
            if (game.player !== null) {
                message = {
                    type: "playerPosition",
                    payload: { x: Math.round(mouseX / ratio), y: Math.round(mouseY / ratio)},
                    sender: game.player.uuid
                };
                if (ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify(message));
                }
            }
        }, refreshTime);
    }

    sendUserPos();
    animate();

// });