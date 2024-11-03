<?php

namespace Api;

use Data\TennisPlayer as DataTennisPlayer;
use Tennis\TennisPlayer as TennisTennisPlayer;

class TennisPlayer {
    public static function add($player) {
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
            return "{\"status\": " . HTTP_BAD_REQUEST . "}";
        }

        return "{\"status\": " . HTTP_OK . ", \"id\": \"" . $row->id . "\"}";
    }

    public static function delete($player_id) {
        $data_tennis_player = new DataTennisPlayer();

        $deleted = $data_tennis_player->delete($player_id);

        if (!$deleted) {
            return "{\"status\": " . HTTP_BAD_REQUEST . "}";
        }

        return "{\"status\": " . HTTP_OK . "}";
    }
}