<?php

namespace Api;

use Data\TennisTournament as DataTennisTournament;
use Tennis\FemaleTennisTournament;
use Tennis\MaleTennisTournament;
use Tennis\TennisPlayer;

class TennisTournament {
    public static function add($tournament) {

        if ($tournament->gender == "M") {
            $tennis_tennis_tournament = new MaleTennisTournament(
                $tournament->name
            );
        } else {
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

        if (!$winner instanceof TennisPlayer) {
            return "{\"status\": " . HTTP_BAD_REQUEST . "}";
        }

        return "{\"status\": " . HTTP_OK . ", \"winner\": \"" . $winner->get_name() . "\"}";
    }
}