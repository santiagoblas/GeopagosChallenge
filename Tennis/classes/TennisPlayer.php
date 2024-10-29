<?php
namespace Tennis;

class TennisPlayer {
    private string $name;
    private int $level;

    public function __construct($name, $level) {
        $this->name = $name;
        $this->level = $level;
    }

    public function get_name() : string {
        return $this->name;
    }

    public function get_level() : int {
        return $this->level;
    }
}