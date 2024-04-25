import { Line } from "./Line.js";

export class Player {
    constructor(game, uuid) {
        this.game = game;
        this.x = Math.floor(Math.random() * (800 - 100 + 1)) + 100;
        this.y = Math.floor(Math.random() * (400 - 50 + 1)) + 50;;
        this.radius = (this.game.width * 0.01);
        this.color = "rgb(191, 175, 0)";
        this.uuid = uuid;
        this.nickname = "JOZEFINA";
        this.lines = [];
    }

    // updates the player positioning only within canvas
    update(posX, posY) {
        this.x = posX;
        this.y = posY;
    }

    draw(ctx) {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, 2 * Math.PI);
        ctx.fillStyle = this.color;
        ctx.fill();
    }

    addLine(x1, y1, x2, y2) {
        this.lines.push(new Line(x1, y1, x2, y2, this.game.width * 0.005));
    }

    drawLines(ctx) {
        this.lines.forEach(line => {
            ctx.strokeStyle = this.color.replace(")", ", 0.7)");
            line.draw(ctx);
        });
    }
} 