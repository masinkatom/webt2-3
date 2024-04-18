export class Player {
    constructor(game, uuid) {
        this.game = game;
        this.x = this.game.width / 2;
        this.y = this.game.height / 2;
        this.radius = (this.game.width * 0.02);
        this.color = "rgb(191, 175, 0)";
        this.uuid = uuid;
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
} 