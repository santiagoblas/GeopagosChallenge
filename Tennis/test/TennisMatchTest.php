<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use Tennis\MaleTennisMatch;
use Tennis\FemaleTennisMatch;
use Tennis\TennisPlayer;
use Tennis\TennisMatch;

class TennisMatchTest extends TestCase {

    public function test_match_dispute() {
        $player_a = new TennisPlayer("David", 40);
        $player_b = new TennisPlayer("Simón", 100);

        $match = new TennisMatch($player_a, $player_b);

        $winner = $match->dispute();

        $this->assertEquals($player_b, $winner);
    }

    public function test_male_match_dispute() {
        $player_a = new TennisPlayer("David", 2, 22, 23, 0.1);
        $player_b = new TennisPlayer("Simón", 80, 80, 45, 1);

        $match = new MaleTennisMatch($player_a, $player_b);

        $winner = $match->dispute();

        $this->assertEquals($player_b, $winner);

        $c_wins = 0;
        $d_wins = 0;

        $player_c = new TennisPlayer("Simón", 65, 60, 45, 1);
        $player_d = new TennisPlayer("David", 45, 30, 23, 0.1);

        $match2 = new MaleTennisMatch($player_c, $player_d);

        for ($i=0; $i < 100; $i++) { 
            $winner = $match2->dispute();

            if ($winner === $player_c) {
                $c_wins++;
            }

            if ($winner === $player_d) {
                $d_wins++;
            }
        }

        $is_random = ( $c_wins > $d_wins ) && ( $d_wins > 0 );

        $this->assertTrue($is_random, "Randomness failed.");
    }

    public function test_female_match_dispute() {
        $player_a = new TennisPlayer("Perla", 80, 30, 60, 0.1);
        $player_b = new TennisPlayer("Ramona", 40, 60, 33, 0.9);

        $match = new FemaleTennisMatch($player_a, $player_b);

        $winner = $match->dispute();

        $this->assertEquals($player_a, $winner, "The best didn't won");

        $c_wins = 0;
        $d_wins = 0;

        $player_c = new TennisPlayer("Perla", 67, 30, 60, 0.3);
        $player_d = new TennisPlayer("Ramona", 55, 60, 33, 0.6);

        $match2 = new FemaleTennisMatch($player_c, $player_d);

        for ($i=0; $i < 100; $i++) { 
            $winner = $match2->dispute();

            if ($winner === $player_c) {
                $c_wins++;
            }

            if ($winner === $player_d) {
                $d_wins++;
            }
        }

        $is_random = ( $c_wins > $d_wins ) && ( $d_wins > 0 );

        $this->assertTrue($is_random, "Randomness failed.");
    }

}