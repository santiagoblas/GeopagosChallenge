<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use Tennis\FemaleTennisTournament;
use Tennis\MaleTennisTournament;
use Tennis\TennisPlayer;
use Tennis\TennisTournament;

class TennisTournamentTest extends TestCase {

    public function test_instantiation() {
        $players = array();
        $players[] = new TennisPlayer("David", 75);
        $players[] = new TennisPlayer("Simón", 80);
        $players[] = new TennisPlayer("Brenda", 58);
        $players[] = new TennisPlayer("Lara", 88);
        $players[] = new TennisPlayer("Zamantha", 45);

        $players_with_intruder = array_merge($players, ["not TennisPlayer"]);

        $tournament = new TennisTournament($players_with_intruder);

        $this->assertEquals($players, $tournament->get_players(), "Should be TennisPlayer validation failed.\n");
    }

    public function test_add_player() {
        $players = array();
        $players[] = new TennisPlayer("David", 75);
        $players[] = new TennisPlayer("Simón", 80);
        $players[] = new TennisPlayer("Brenda", 58);
        $players[] = new TennisPlayer("Lara", 88);

        $tournament = new TennisTournament($players);

        $new_player = new TennisPlayer("Zamantha", 45);

        $tournament->add_player($new_player);

        $was_new_player_added = in_array($new_player, $tournament->get_players());

        $this->assertTrue($was_new_player_added, "TennisPlayer not added.\n");
    }

    public function test_can_dispute() {
        $players[] = new TennisPlayer("David", 75);
        $players[] = new TennisPlayer("Simón", 80);
        $players[] = new TennisPlayer("Brenda", 58);
        $players[] = new TennisPlayer("Lara", 88);

        $tournament = new TennisTournament($players);

        $this->assertTrue($tournament->can_dispute());
    }

    public function test_tournament_dispute() {
        $players = array();
        $players[] = new TennisPlayer("David", 22);
        $players[] = new TennisPlayer("Simón", 22);
        $players[] = new TennisPlayer("Brenda", 22);

        $lara = new TennisPlayer("Lara", 100);
        $players[] = $lara;

        $tournament = new TennisTournament($players);

        $this->assertEquals($lara, $tournament->dispute(), "The best didn't won.\n");
    }

    public function test_male_tournament_dispute() {
        $players = array();
        $players[] = new TennisPlayer("David", 22, 50, 34, 0.1);
        $players[] = new TennisPlayer("Simón", 32, 45, 55, 0.1);
        $players[] = new TennisPlayer("Brenda", 28, 56, 43, 0.1);

        $lara = new TennisPlayer("Lara", 99, 99, 88, 1);
        $players[] = $lara;

        $tournament = new MaleTennisTournament($players);

        $this->assertEquals($lara, $tournament->dispute(), "The best didn't won.\n");
    }

    public function test_female_tournament_dispute() {
        $players = array();
        $players[] = new TennisPlayer("David", 22, 99, 99, 0.8);
        $players[] = new TennisPlayer("Simón", 32, 99, 99, 0.8);
        $players[] = new TennisPlayer("Brenda", 28, 99, 99, 0.9);

        $lara = new TennisPlayer("Lara", 88, 2, 8, 0.1);
        $players[] = $lara;

        $tournament = new FemaleTennisTournament($players);



        $this->assertEquals($lara, $tournament->dispute(), "The best didn't won.\n");
    }
}