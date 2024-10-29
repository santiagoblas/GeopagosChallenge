<?php
namespace Test;

use PHPUnit\Framework\TestCase;
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

    public function test_dispute() {
        $players = array();
        $players[] = new TennisPlayer("David", 75);
        $players[] = new TennisPlayer("Simón", 80);
        $players[] = new TennisPlayer("Brenda", 58);

        $lara = new TennisPlayer("Lara", 88);
        $players[] = $lara;

        $tournament = new TennisTournament($players);

        $this->assertEquals($lara, $tournament->dispute(), "The best didn't won.\n");
    }
}