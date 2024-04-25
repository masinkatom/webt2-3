<?php

class Line implements JsonSerializable{
    private $x1;
    private $y1;
    private $x2;
    private $y2;

    public function __construct($x1, $y1, $x2, $y2) {
        $this->x1 = $x1;
        $this->y1 = $y1;
        $this->x2 = $x2;
        $this->y2 = $y2;
    }

    public function jsonSerialize() {
        return [
            'x1' => $this->x1,
            'y1' => $this->y1,
            'x2' => $this->x2,
            'y2' => $this->y2
            // Add more properties as needed
        ];
    }

    public function getX1() {
        return $this->x1;
    }

    public function getX2() {
        return $this->x2;
    }
    public function getY1() {
        return $this->y1;
    }
    public function getY2() {
        return $this->y2;
    }

}