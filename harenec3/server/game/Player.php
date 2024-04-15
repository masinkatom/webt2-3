<?php

class Player {
    private $game;
    private $x;
    private $y;
    private $radius;
    private $color;
    private $uuid;

    public function __construct($game, $uuid) {
        $this->game = $game;
        $this->x = $this->game->getWidth() / 2;
        $this->y = $this->game->getHeight() / 2;
        $this->radius = $this->game->getWidth() * 0.02;
        $this->color = "rgb(191, 175, 0)";
        $this->uuid = $uuid;
    }

    // updates the player positioning only within canvas
    public function update($mouseX, $mouseY) {
        if ($mouseX != -1 && $mouseY != -1) {
            $dx = $mouseX - $this->x;
            $dy = $mouseY - $this->y;
            $distance = sqrt($dx * $dx + $dy * $dy);
    
            // Move the player towards the cursor at a constant speed
            if ($distance > $this->game->getBallSpeed()) {
                $this->x += ($dx / $distance) * $this->game->getBallSpeed();
                $this->y += ($dy / $distance) * $this->game->getBallSpeed();
            }
            else {
                $this->x = $mouseX;
                $this->y = $mouseY;
            }
        }
    }
}

