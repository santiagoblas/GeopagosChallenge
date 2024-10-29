<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use Tennis\TennisPlayer;
use Tennis\TennisMatch;

class TennisMatchTest extends TestCase {

    public function test_match_dispute() {
        $player_a = new TennisPlayer("David", 75);
        $player_b = new TennisPlayer("Simón", 80);

        $match = new TennisMatch($player_a, $player_b);

        $winner = $match->dispute();

        $this->assertEquals($player_b, $winner);
    }

}