import { Line } from "./Line.js";

export class Player {
    constructor(game, uuid) {
        this.game = game;
        this.x = 500;
        this.y = 250;
        this.radius = (this.game.width * 0.02);
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
        this.drawLines(ctx);
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, 2 * Math.PI);
        ctx.fillStyle = this.color;
        ctx.fill();
    }

    addLine(x1, y1, x2, y2) {
        this.lines.push(new Line(x1, y1, x2, y2));
    }

    drawLines(ctx) {
        this.lines.forEach(line => {
            line.draw(ctx);
        });
    }
} 