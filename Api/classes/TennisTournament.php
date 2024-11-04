<?php

namespace Api;

use Data\TennisTournament as DataTennisTournament;
use Tennis\FemaleTennisTournament;
use Tennis\MaleTennisTournament;
use Tennis\TennisPlayer as TennisTennisPlayer;

class TennisTournament {
    public static function add($tournament) {
        $tennis_tennis_tournament = null;

        if ($tournament->gender == "M") {
            $tennis_tennis_tournament = new MaleTennisTournament(
                $tournament->name
            );
        } else if ($tournament->gender == "F") {
            $tennis_tennis_tournament = new FemaleTennisTournament(
                $tournament->name
            );
        }

        $data_tennis_tournament = new DataTennisTournament($tennis_tennis_tournament);

        $row = $data_tennis_tournament->save();

        if (is_null($row->id)) {
            return "{\"status\": " . HTTP_BAD_REQUEST . "}";
        }

        return "{\"status\": " . HTTP_OK . ", \"id\": \"" . $row->id . "\"}";
    }

    public static function delete($tournament_id) {
        $data_tennis_tournament = new DataTennistournament();

        $deleted = $data_tennis_tournament->delete($tournament_id);

        if (!$deleted) {
            return "{\"status\": " . HTTP_BAD_REQUEST . "}";
        }

        return "{\"status\": " . HTTP_OK . "}";
    }

    public static function dispute($tournament_id) {
        $data_tennis_tournament = new DataTennistournament();

        $tennis_tournament = $data_tennis_tournament->find($tournament_id);

        $winner = $tennis_tournament->dispute();

        if (!$winner instanceof TennisTennisPlayer) {
            return "{\"status\": " . HTTP_BAD_REQUEST . "}";
        }

        return "{\"status\": " . HTTP_OK . ", \"winner\": \"" . $winner->get_name() . "\"}";
    }

    public static function fast_tournament($players, $tournament) {
        $player_ids = [];
        foreach ($players as $player) {
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
                return "{\"status\": " . HTTP_BAD_REQUEST . ", \"message\": \"Players save failed\"}";
            }

            $player_ids[] = $player_id;
        }

        $result = TennisTournament::add($tournament);
        $result = json_decode($result);
        if ($result->status != 200) {
            return "{\"status\": " . HTTP_BAD_REQUEST . ", \"message\": \"Tournament save failed\", \"player_ids\": " . json_encode($player_ids) . "}";
        }
        $tournament_id = $result->id;

        $result = TennisRegistration::register_lot_by_ids($tournament_id, $player_ids);
        $result = json_decode($result);
        if ($result->status != 200) {
            return "{\"status\": " . HTTP_BAD_REQUEST . ", \"message\": \"Registrations save failed\"}";
        }

        return self::dispute($tournament_id);
    }
}