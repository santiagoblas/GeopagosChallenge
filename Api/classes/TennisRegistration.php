<?php

namespace Api;

use Data\TennisRegistration as DataTennisRegistration;

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
            return "{\"status\": " . HTTP_BAD_REQUEST . "}";
        }

        $model = new DataTennisRegistration();

        $model->tournament_id = $tournament_id;
        $model->player_id = $player_id;

        $row = $model->save();

        if (is_null($row->id)) {
            return "{\"status\": " . HTTP_BAD_REQUEST . "}";
        }

        return "{\"status\": " . HTTP_OK . ", \"id\": \"" . $row->id . "\"}";
    }

    public static function register_lot_by_ids($tournament_id, $player_ids) {
        $registration_ids = [];
        foreach ($player_ids as $player_id) {
            if ($player_id == null) {
                return "{\"status\": " . HTTP_BAD_REQUEST . "}";
            }
    
            $model = new DataTennisRegistration();
    
            $model->tournament_id = $tournament_id;
            $model->player_id = $player_id;
    
            $row = $model->save();
    
            if (is_null($row->id)) {
                return "{\"status\": " . HTTP_BAD_REQUEST . ", \"message\": \"Registrations save failed\"}";
            }

            $registration_ids[] = $row->id;
        }

        return "{\"status\": " . HTTP_OK . ", \"ids\": " . json_encode($registration_ids) . "}";
    }

}