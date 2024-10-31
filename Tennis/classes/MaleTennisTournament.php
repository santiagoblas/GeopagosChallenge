<?php

namespace Tennis;

class MaleTennisTournament extends TennisTournament {
    private function get_tennis_match(TennisPlayer $player_a, TennisPlayer $player_b) : TennisMatch {
        $match = new MaleTennisMatch($player_a, $player_b);

        return $match;
    }
}