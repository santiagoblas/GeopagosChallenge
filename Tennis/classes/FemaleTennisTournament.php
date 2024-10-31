<?php

namespace Tennis;

class FemaleTennisTournament extends TennisTournament {
    private function get_tennis_match(TennisPlayer $player_a, TennisPlayer $player_b) : TennisMatch {
        $match = new FemaleTennisMatch($player_a, $player_b);

        return $match;
    }
}