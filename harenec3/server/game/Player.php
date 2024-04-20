<?php

require_once 'Line.php';

class Player implements JsonSerializable{
    private $game;
    private $x;
    private $y;
    private $prevX;
    private $prevY;
    private $radius;
    private $ballSpeed;
    private $color;
    private $uuid;
    private $nickname;
    private $lines;

    public function __construct($game, $uuid) {
        $this->game = $game;
        $this->x = $this->game->getWidth() / 2;
        $this->y = $this->game->getHeight() / 2;
        $this->radius = $this->game->getWidth() * 0.02;
        $this->ballSpeed = 2 * $this->game->getRefreshTimeSeconds() * 100;
        $this->color = "rgb(191, 175, 0)";
        $this->uuid = $uuid;
        $this->nickname = "JOZEFINA";
        $this->lines = [];
        $this->prevX = $this->x;
        $this->prevY = $this->y;

    }

    // updates the player positioning only within canvas
    public function update($mouseX, $mouseY) {
        if ($mouseX != -1 && $mouseY != -1) {
            $this->prevX = $this->x;
            $this->prevY = $this->y;
            
            $dx = ($mouseX - $this->x);
            $dy = ($mouseY - $this->y);
            $distance = sqrt(($dx * $dx) + ($dy * $dy));
    
            // Move the player towards the cursor at a constant speed
            if ($distance > $this->ballSpeed) {
                $this->x += round(($dx / $distance) * $this->ballSpeed);
                $this->y += round(($dy / $distance) * $this->ballSpeed);
            }
            else {
                $this->x = round($mouseX);
                $this->y = round($mouseY);
            }

            array_push(
                $this->lines, 
                new Line($this->prevX, $this->prevY, $this->x, $this->y)
            );
            // echo "\n" .$this->uuid . ": x: " . $this->x . " y: " . $this->y;
        }
    }

    public function jsonSerialize() {
        $linesData = [];
        foreach ($this->lines as $line) {
            array_push($linesData, $line->jsonSerialize());
        }
        return [
            'x' => $this->x,
            'y' => $this->y,
            'radius' => $this->radius,
            'ballSpeed' => $this->ballSpeed,
            'color' => $this->color,
            'uuid' => $this->uuid,
            'lines' => $linesData
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

