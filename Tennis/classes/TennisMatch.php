<?php
namespace Tennis;

class TennisMatch {
    protected TennisPlayer $player_a;
    protected TennisPlayer $player_b;

    public function __construct(TennisPlayer $player_a, TennisPlayer $player_b)
    {
        $this->player_a = $player_a;
        $this->player_b = $player_b;
    }

    public function dispute() : TennisPlayer {
        if ( $this->player_performance($this->player_a) >= $this->player_performance($this->player_b) ) {
            return $this->player_a;
        }

        return $this->player_b;
    }

    private function player_performance(TennisPlayer $player) : int {
        $rng = $this->generate_performance_rng();

        $rng_level = $rng * (float) $player->get_level();

        $performance = 0;

        $performance += $rng_level;

        return floor($performance);
    }

    protected function generate_performance_rng() : float {
        $rng = mt_rand( 0, mt_getrandmax() - 1 ) / ( mt_getrandmax() * 2 ) + 0.5;

        return $rng;
    }
}