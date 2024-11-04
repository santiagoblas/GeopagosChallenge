<?php

namespace Api;

use Data\TennisPlayer as DataTennisPlayer;
use Tennis\TennisPlayer as TennisTennisPlayer;

class TennisPlayer {
    public static function add($player) {
        $is_id_null = $player->id == null;
        $is_rest_completed = true;
        foreach ((array) $player as $key => $value) {
            if ($key == "id") {
                continue;
            }

            if (is_null($value)) {
                $is_rest_completed = false;
                break;
            }
        }
        if($is_id_null && !$is_rest_completed) {
            return "{\"status\": 400}";
        }
        
        $tennis_tennis_player = new TennisTennisPlayer(
            $player->name, 
            $player->level, 
            $player->strength, 
            $player->speed, 
            $player->reaction_time
        );

        $data_tennis_player = new DataTennisPlayer($tennis_tennis_player);

        $row = $data_tennis_player->save();

        if (is_null($row->id)) {
            return "{\"status\": 400}";
        }

        return "{\"status\": 200, \"id\": \"" . $row->id . "\"}";
    }

    public static function delete($player_id) {
        $data_tennis_player = new DataTennisPlayer();

        $deleted = $data_tennis_player->delete($player_id);

        if (!$deleted) {
            return "{\"status\": 400}";
        }

        return "{\"status\": 200}";
    }
}