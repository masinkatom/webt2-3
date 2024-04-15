export class Player {
    constructor(game) {
        this.game = game;
        this.x = this.game.width / 2;
        this.y = this.game.height / 2;
        this.radius = (this.game.width * 0.02);
        this.color = "rgb(191, 175, 0)";
        this.uuid = "";
    }

    // updates the player positioning only within canvas
    update(mouseX, mouseY) {
        if (mouseX != -1 && mouseY != -1) {
            // const dx = mouseX - this.x;
            // const dy = mouseY - this.y;
            // const distance = Math.sqrt(dx * dx + dy * dy);
    
            // // Move the ball towards the cursor at a constant speed
            // if (distance > this.game.ballSpeed) {
            //     this.x += (dx / distance) * this.game.ballSpeed;
            //     this.y += (dy / distance) * this.game.ballSpeed;
            // }
            // else {
            //     this.x = mouseX;
            //     this.y = mouseY;
            // }
            
        }
    }

    draw(ctx) {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, 2 * Math.PI);
        ctx.fillStyle = this.color;
        ctx.fill();
    }
} 