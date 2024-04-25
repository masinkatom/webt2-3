import { Player } from "./Player.js";

export class Game {
    constructor(width, height) {
        this.width = width;
        this.height = height;
        this.player = null;
        this.enemies = [];
        this.zoomLevel = 1.8;
       
    }

    // method to updatee items on canvas, called from animate every couple miliseconds
    update() {

    }
    // method to draw items on canvas, called from animate every couple miliseconds
    draw(ctx) {

        // Translate the canvas to the zoom center
        ctx.translate(this.player.x, this.player.y);

        // Scale the canvas context to zoom in
        ctx.scale(this.zoomLevel, this.zoomLevel);

        // Translate the canvas back to its original position
        ctx.translate(-this.player.x, -this.player.y);

        // Draw your objects or perform other canvas operations here
        this.player.drawLines(ctx);
        this.enemies.forEach(enemy => {
            enemy.drawLines(ctx);
        });

        this.player.draw(ctx);
        this.enemies.forEach(enemy => {
            enemy.draw(ctx);
        });

        // These operations will be scaled and centered around the zoomCenterX and zoomCenterY

        // Reset the transformation to prevent affecting subsequent drawings
        ctx.setTransform(1, 0, 0, 1, 0, 0);

        


    }

    addEnemy(uuid) {
        let exists = this.checkIfExists();

        if (exists === false) {
            this.enemies.push(new Player(this, uuid));
        }
    }

    removeEnemy(uuid) {
        let exists = this.checkIfExists(uuid);

        if (exists !== false) {
            this.enemies = this.enemies.filter(enemy => enemy.uuid !== exists.uuid);
        }
    }

    // returns Player if exists, false if not
    checkIfExists(uuid) {
        let value = false;

        this.enemies.forEach(enemy => {
            if (enemy.uuid === uuid) {
                value = enemy;
                return;
            }
        });

        return value;
    }

}