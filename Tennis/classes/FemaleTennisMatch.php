<?php

namespace Tennis;

class FemaleTennisMatch extends TennisMatch {
    private function player_performance(TennisPlayer $player) {
        $rng = $this->generate_performance_rng();

        $rng_level = $rng * $player->get_level();

        $performance = 0;

        $performance += $rng_level + (1 - min($player->get_reaction_time(), 1)) * 100;

        return floor($performance);
    }
}