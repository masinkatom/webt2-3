import { Player } from "./Player.js";

export class Game {
    constructor(width, height) {
        this.width = width;
        this.height = height;
        this.player = new Player(this, "");
        this.enemies = [];
    }

    // method to updatee items on canvas, called from animate every couple miliseconds
    update() {

    }

    // method to draw items on canvas, called from animate every couple miliseconds
    draw(ctx) {
        this.player.draw(ctx);
        this.enemies.forEach(enemy => {
            enemy.draw(ctx);
        });

    }

    addEnemy(uuid) {
        this.enemies.push(new Player(this, uuid));
    }

}