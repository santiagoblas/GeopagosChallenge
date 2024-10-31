<?php
namespace Tennis;

class TennisPlayer {
    private string $name;
    private int $level;
    private int $strength;
    private int $speed;
    private float $reaction_time;

    public function __construct($name, $level, $strength = 20, $speed = 25, $reaction_time = 0.5) {
        $this->name = $name;
        $this->level = $level;
        $this->strength = $strength;
        $this->speed = $speed;
        $this->reaction_time = $reaction_time;
    }

    public function get_name() : string {
        return $this->name;
    }

    public function get_level() : int {
        return $this->level;
    }

    public function get_strength() : int {
        return $this->strength;
    }

    public function get_speed() : int {
        return $this->speed;
    }

    public function get_reaction_time() : float {
        return $this->reaction_time;
    }
}