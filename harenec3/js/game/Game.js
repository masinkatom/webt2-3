import { EnemyBar } from "./EnemyBar.js";
import { Player } from "./Player.js";

export class Game {
    constructor() {


    }

    // method to updatee items on canvas, called from animate every couple miliseconds
    update(deltaTime) {

    }

    // method to draw items on canvas, called from animate every couple miliseconds
    draw(ctx) {

    }

    handleGyro(e) {
        // gamma: left to right
        this.player.update(e.gamma * 0.4);
    }


}