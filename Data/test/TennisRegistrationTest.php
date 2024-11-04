<?php

namespace Data;

use Data\TennisTournament;
use PHPUnit\Framework\TestCase;
use Tennis\TennisPlayer as TennisTennisPlayer;
use Tennis\TennisRegistration as TennisTennisRegistration;
use Tennis\TennisTournament as TennisTennisTournament;

class TennisRegistrationTest extends TestCase {

    public function test_save_tournament() : int {
        $tournament = new TennisTennisTournament("test tournament", []);

        $model = new TennisTournament($tournament);

        $tennis_tournament = $model->save();

        $id = $tennis_tournament->id;

        $this->assertFalse(is_null($id), "Tennis tournament not saved");

        return $id;
    }
    
    public function test_save_players() : array {
        $model = new TennisPlayer();

        $players = [];
        $players[] = new TennisTennisPlayer("David", 75,1,1,1);
        $players[] = new TennisTennisPlayer("Simón", 80,1,1,1);
        $players[] = new TennisTennisPlayer("Brenda", 58,1,1,1);
        $players[] = new TennisTennisPlayer("Lara", 88,1,1,1);

        $all_saved = true;
        $player_ids = [];
        foreach ($players as $player) {
            $model->player = $player;
            $tennis_player = $model->save();
            $id = $tennis_player->id;

            if (is_null($id)) {
                $all_saved = false;
            }

            $player_ids[] = $id;
        }

        $this->assertTrue($all_saved, "Tennis players not saved");

        return $player_ids;
    }

    /**
     * @depends test_save_tournament
     * @depends test_save_players
     * */
    public function test_register_players(int $tournament_id, array $player_ids) {
        $model = new TennisRegistration();

        $all_saved = true;
        foreach ($player_ids as $player_id) {
            $data_tennis_player = new TennisPlayer();
            $tennis_tennis_player = $data_tennis_player->find($player_id);

            $data_tennis_tournament = new TennisTournament();
            $tennis_tennis_tournament = $data_tennis_tournament->find($tournament_id);

            $model->registration = new TennisTennisRegistration($tennis_tennis_player, $tennis_tennis_tournament); 

            $tennis_registration = $model->save();

            $id = $tennis_registration->id;

            if (is_null($id)) {
                $all_saved = false;
            }

            $registration_ids[] = $id;
        }

        $this->assertTrue($all_saved, "Tennis registrations not saved");

        return $registration_ids;
    }

    /**
     * @depends test_save_tournament
     * @depends test_register_players
     * */
    public function test_delete_tournament($tournament_id) {
        $tennis_tournament = new TennisTournament();

        $deleted = $tennis_tournament->delete($tournament_id);

        $this->assertTrue($deleted, "Some Deletes Failed");
    }

    /**
     * @depends test_save_players
     * @depends test_register_players
     * */
    public function test_delete_players($player_ids) {
        $tennis_player = new TennisPlayer();
        
        $all_deleted = true;
        foreach ($player_ids as $player_id) {
            $deleted = $tennis_player->delete($player_id);

            if (!$deleted) {
                $all_deleted = false;
            }
        }

        $this->assertTrue($all_deleted, "Some Deletes Failed");
    }

    /**
     * @depends test_register_players
     * */
    public function test_delete_registrations($registration_ids) {
        $tennis_registration = new TennisRegistration();

        $all_deleted = true;
        foreach ($registration_ids as $registration_id) {
            $deleted = $tennis_registration->find($registration_id);

            if (!is_null($deleted)) {
                $all_deleted = false;
            }
        }

        $this->assertTrue($all_deleted, "Some Deletes Failed");
    }


}