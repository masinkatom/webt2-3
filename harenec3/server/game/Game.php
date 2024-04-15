<?php

class Game {
    private $width;
    private $height;
    private $ratio;
    private $ballSpeed;
    private $players;
    
    public function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height;
        $this->ratio = 1 * ($this->width / 1000);
        $this->ballSpeed = 3 * $this->ratio;
        $this->players = [];
    }

    public function update($mouseX, $mouseY) {
        $this->players->update($mouseX, $mouseY);
    }

    // Getter methods
    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getRatio() {
        return $this->ratio;
    }

    public function getBallSpeed() {
        return $this->ballSpeed;
    }

    public function getPlayers() {
        return $this->players;
    }

    // Add an enemy to the game
    public function addPlayer($player) {
        array_push($this->players, $player);
    }
}

