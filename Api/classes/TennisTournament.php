<?php

namespace Api;

use Data\TennisTournament as DataTennisTournament;
use Tennis\FemaleTennisTournament;
use Tennis\MaleTennisTournament;
use Tennis\TennisPlayer as TennisTennisPlayer;
use Tennis\TennisTournament as TennisTennisTournament;

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
            return "{\"status\": 400}";
        }

        return "{\"status\": 200, \"id\": \"" . $row->id . "\"}";
    }

    public static function delete($tournament_id) {
        $data_tennis_tournament = new DataTennistournament();

        $deleted = $data_tennis_tournament->delete($tournament_id);

        if (!$deleted) {
            return "{\"status\": 400}";
        }

        return "{\"status\": 200}";
    }

    public static function dispute($tournament_id) {
        $data_tennis_tournament = new DataTennistournament();

        $tennis_tennis_tournament = $data_tennis_tournament->find($tournament_id);

        if ($tennis_tennis_tournament->get_disputed()) {
            $winner = $tennis_tennis_tournament->get_winner();
            return "{\"status\": 200, \"winner\": \"" . $winner->get_name() . "\", \"date\": \"" . $tennis_tennis_tournament->get_date() . "\", \"message\": \"This tournament was already disputed\"}"; 
        }

        if (!$tennis_tennis_tournament->can_dispute()) {
            return "{\"status\": 400, \"message\": \"Can't dispute tournament, players not a power of 2\"}";
        }

        $winner = $tennis_tennis_tournament->dispute();

        if (!$winner instanceof TennisTennisPlayer) {
            return "{\"status\": 400}";
        }

        self::register_dispute($tennis_tennis_tournament, $data_tennis_tournament);

        TennisRegistration::register_winner($winner, $tennis_tennis_tournament);

        return "{\"status\": 200, \"winner\": \"" . $winner->get_name() . "\"}";
    }

    private static function register_dispute(TennisTennisTournament $tennis_tennis_tournament, DataTennisTournament $data_tennis_tournament) {
        $tennis_tennis_tournament->set_date(date("Y-m-d 00:00:00"));
        $tennis_tennis_tournament->set_disputed(true);

        $data_tennis_tournament->set_tournament($tennis_tennis_tournament);
        $data_tennis_tournament->update();
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
                return "{\"status\": 400, \"message\": \"Players save failed\"}";
            }

            $player_ids[] = $player_id;
        }

        $result = TennisTournament::add($tournament);
        $result = json_decode($result);
        if ($result->status != 200) {
            return "{\"status\": 400, \"message\": \"Tournament save failed\", \"player_ids\": " . json_encode($player_ids) . "}";
        }
        $tournament_id = $result->id;

        $result = TennisRegistration::register_lot_by_ids($tournament_id, $player_ids);
        $result = json_decode($result);
        if ($result->status != 200) {
            return "{\"status\": 400, \"message\": \"Registrations save failed\"}";
        }

        return self::dispute($tournament_id);
    }

    public static function search_tournament($params) {
        $is_at_least_one_setted = false;
        foreach ($params as $param) {
            if (!is_null($param)) {
                $is_at_least_one_setted = true;
                break;
            }
        }

        if (!$is_at_least_one_setted) {
            return "{\"status\": 400}, \"message\": \"No Filter applied\"}";
        }

        $data_tennis_tournament = new DataTennistournament();

        $tournaments = $data_tennis_tournament->search($params);

        $tournaments = array_map(function($tournament) {
            return $tournament->get_id();
        }, $tournaments);

        return "{\"status\": 200, \"tournament_ids\": \"" . json_encode($tournaments) . "\"}";
    }
}