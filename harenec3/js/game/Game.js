import { Player } from "./Player.js";

export class Game {
    constructor(width, height) {
        this.width = width;
        this.height = height;
        this.player = null;
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