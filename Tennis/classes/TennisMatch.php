<?php
namespace Tennis;

class TennisMatch {
    private TennisPlayer $player_a;
    private TennisPlayer $player_b;

    public function __construct(TennisPlayer $player_a, TennisPlayer $player_b)
    {
        $this->player_a = $player_a;
        $this->player_b = $player_b;
    }

    public function dispute() : TennisPlayer {
        if ( $this->player_a->get_level() >= $this->player_b->get_level() ) {
            return $this->player_a;
        }

        return $this->player_b;
    }
}