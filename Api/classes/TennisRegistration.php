<?php

namespace Api;

use Data\TennisPlayer as DataTennisPlayer;
use Data\TennisRegistration as DataTennisRegistration;
use Data\TennisTournament as DataTennisTournament;
use Tennis\TennisPlayer as TennisTennisPlayer;
use Tennis\TennisRegistration as TennisTennisRegistration;
use Tennis\TennisTournament as TennisTennisTournament;

class TennisRegistration {
    public static function register($tournament_id, $player) {
        $player_id = null;
        if ($player->id == null) {
            $result = TennisPlayer::add($player);
            $result = json_decode($result);
            
            if ($result->status == 200) {
                $player_id = $result->id;
            }
        } else {
            $player_id = $player->id;
        }

        if ($player_id == null) {
            return "{\"status\": 400}";
        }

        $model = new DataTennisRegistration();

        $model->registration = self::build_registration($player_id, $tournament_id);

        $row = $model->save();

        if (is_null($row->id)) {
            return "{\"status\": 400}";
        }

        return "{\"status\": 200, \"id\": \"" . $row->id . "\"}";
    }

    public static function register_lot_by_ids($tournament_id, $player_ids) {
        $registration_ids = [];
        foreach ($player_ids as $player_id) {
            if ($player_id == null) {
                return "{\"status\": 400}";
            }
    
            $model = new DataTennisRegistration();

            $model->registration = self::build_registration($player_id, $tournament_id);
    
            $row = $model->save();
    
            if (is_null($row->id)) {
                return "{\"status\": 400, \"message\": \"Registrations save failed\"}";
            }

            $registration_ids[] = $row->id;
        }

        return "{\"status\": 200, \"ids\": " . json_encode($registration_ids) . "}";
    }

    private static function build_registration(int $player_id, int $tournament_id) : TennisTennisRegistration {
        $data_tennis_player = new DataTennisPlayer();
        $tennis_tennis_player = $data_tennis_player->find($player_id);

        $data_tennis_tournament = new DataTennisTournament();
        $tennis_tennis_tournament = $data_tennis_tournament->find($tournament_id);

        return new TennisTennisRegistration($tennis_tennis_player, $tennis_tennis_tournament); 
    }

    public static function register_winner(TennisTennisPlayer $winner, TennisTennisTournament $tournament) {
        $model = new DataTennisRegistration();

        $tennis_tennis_registrations = $model->find_all_by_tournament($tournament);
        $tennis_tennis_registration = null;
        foreach ($tennis_tennis_registrations as $registration) {
            if ($winner->get_id() == $registration->get_player()->get_id()) {
                $tennis_tennis_registration = $registration;
                break;
            }
        }

        if (is_null($tennis_tennis_registration)) {
            return;
        }

        $tennis_tennis_registration->set_win(true);
        $model->registration = $tennis_tennis_registration;

        $model->update();
    }
}