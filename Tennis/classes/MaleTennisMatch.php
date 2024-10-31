<?php

namespace Tennis;

class MaleTennisMatch extends TennisMatch {
    private function player_performance(TennisPlayer $player) {
        $rng = $this->generate_performance_rng();

        $rng_level = $rng * $player->get_level();

        $performance = 0;

        $performance += $rng_level + $player->get_strength() + $player->get_speed();

        return floor($performance);
    }
}