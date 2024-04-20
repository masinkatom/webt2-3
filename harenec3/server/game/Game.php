<?php

class Game implements JsonSerializable {
    private $width;
    private $height;
    private $players;
    private $tickrate;
    private $refreshTimeSeconds;
    
    public function __construct($width, $height, $tickrate) {
        $this->width = $width;
        $this->height = $height;
        $this->players = [];
        $this->tickrate = $tickrate;
        $this->refreshTimeSeconds = (1000 / $tickrate) / 1000;
    }

    public function update($mouseX, $mouseY) {
        foreach ($this->players as $player) {
            $player->update($mouseX, $mouseY);
        }
        
    }

    // Getter methods
    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getPlayers() {
        return $this->players;
    }

    public function getTickrate() {
        return $this->tickrate;
    }

    public function getRefreshTimeSeconds() {
        return $this->refreshTimeSeconds;
    }

    public function jsonSerialize() {
        $playersData = [];
        foreach ($this->players as $player) {
            array_push($playersData, $player->jsonSerialize());
            // $playersData[] = $player->jsonSerialize();
        }
        return [
            'width' => $this->width,
            'height' => $this->height,
            'players' => $playersData
        ];
    }

    // Add an enemy to the game
    public function addPlayer($player) {
        array_push($this->players, $player);
    }

    public function removePlayer($playerUuidToRemove) {
        foreach ($this->players as $key => $player) {
            
            if ($player->getUuid() === $playerUuidToRemove) {
                echo "\nremoving on key: " . $key . " :";
                var_dump(json_encode($player));
                array_splice($this->players, $key, 1);
                //unset($this->players[$key]);
                return; // Player found and removed, exit the loop
            }
        }
    }
}

