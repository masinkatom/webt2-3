<?php

class Player implements JsonSerializable{
    private $game;
    private $x;
    private $y;
    private $radius;
    private $ballSpeed;
    private $color;
    private $uuid;
    private $nickname;

    public function __construct($game, $uuid) {
        $this->game = $game;
        $this->x = $this->game->getWidth() / 2;
        $this->y = $this->game->getHeight() / 2;
        $this->radius = $this->game->getWidth() * 0.02;
        $this->ballSpeed = 2 * $this->game->getRefreshTimeSeconds() * 100;
        $this->color = "rgb(191, 175, 0)";
        $this->uuid = $uuid;
        $this->nickname = "JOZEFINA";
    }

    // updates the player positioning only within canvas
    public function update($mouseX, $mouseY) {
        if ($mouseX != -1 && $mouseY != -1) {
            $dx = ($mouseX - $this->x);
            $dy = ($mouseY - $this->y);
            $distance = sqrt(($dx * $dx) + ($dy * $dy));
    
            // Move the player towards the cursor at a constant speed
            if ($distance > $this->ballSpeed) {
                $this->x += ($dx / $distance) * $this->ballSpeed;
                $this->y += ($dy / $distance) * $this->ballSpeed;
            }
            else {
                $this->x = $mouseX;
                $this->y = $mouseY;
            }
            // echo "\n" .$this->uuid . ": x: " . $this->x . " y: " . $this->y;
        }
    }

    public function jsonSerialize() {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'radius' => $this->radius,
            'ballSpeed' => $this->ballSpeed,
            'color' => $this->color,
            'uuid' => $this->uuid
            // Add more properties as needed
        ];
    }

    public function getUuid() {
        return $this->uuid;
    }

    public function getBallSpeed() {
        return $this->ballSpeed;
    }
}

