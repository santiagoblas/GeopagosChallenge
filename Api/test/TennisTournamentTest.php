<?php

namespace Api;

use PHPUnit\Framework\TestCase;
use stdClass;

class TennisTournamentTest extends TestCase {
    public function test_fast_tournament() {
        $players = '[
            {
                "name": "Dario",
                "level": 23,
                "strength": 23,
                "speed": 23,
                "reaction_time": 0.5
            },
            {
                "name": "Maria",
                "level": 100,
                "strength": 23,
                "speed": 23,
                "reaction_time": 0.5
            },
            {
                "name": "Carlo",
                "level": 23,
                "strength": 23,
                "speed": 23,
                "reaction_time": 0.5
            },
            {
                "name": "Silvana",
                "level": 100,
                "strength": 23,
                "speed": 23,
                "reaction_time": 0.5
            }
        ]';

        $name = "new tournament";
        $gender = "M";

        $params = [
            "players" => $players,
            "name" => $name,
            "gender" => $gender
        ];

        $players = [];
        foreach (json_decode($params["players"]) as $player) {
            $clean_player = new stdClass();

            $clean_player->id = isset($player->id) ? $player->id : null;
            $clean_player->name = isset($player->name) ? $player->name : null;
            $clean_player->level = isset($player->level) ? $player->level : null;
            $clean_player->strength = isset($player->strength) ? $player->strength : null;
            $clean_player->speed = isset($player->speed) ? $player->speed : null;
            $clean_player->reaction_time = isset($player->reaction_time) ? $player->reaction_time : null;

            $players[] = $clean_player;
        }

        $tournament = new stdClass();
        $tournament->name = $params["name"];
        $tournament->gender = $params["gender"];

        $result = TennisTournament::fast_tournament($players, $tournament);
        $result = json_decode($result);

        $status = $result->status;

        $this->assertEquals($status, 200, "Tournament execution failed");
    }
}