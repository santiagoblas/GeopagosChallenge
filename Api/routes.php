<?php

namespace Api;

use stdClass;

function hello()
{
    return 'Hello world!';
}

dispatch_post("/player/add", function () {
    $player = new stdClass();

    $player->name = $_POST["name"];
    $player->level = $_POST["level"];
    $player->strength = $_POST["strength"];
    $player->speed = $_POST["speed"];
    $player->reaction_time = $_POST["reaction_time"];

    return TennisPlayer::add($player);
});

dispatch_delete("/player/delete/:player_id", function () {
    $player_id = params("player_id");

    return TennisPlayer::delete($player_id);
});

dispatch_post("/tournament/add", function () {
    $tournament = new stdClass();

    $tournament->name = $_POST["name"];
    $tournament->gender = $_POST["gender"];

    return TennisTournament::add($tournament);
});

dispatch_delete("/tournament/delete/:tournament_id", function () {
    $tournament_id = params("tournament_id");

    return TennisTournament::delete($tournament_id);
});

dispatch_get("/tournament/dispute/:tournament_id", function () {
    $tournament_id = params("tournament_id");

    return TennisTournament::dispute($tournament_id);
});

dispatch_post("/tournament/register/:tournament_id", function () {
    $tournament_id = params("tournament_id");

    $player = new stdClass();

    $player->id = isset($_POST["id"]) ? $_POST["id"] : null;
    $player->name = isset($_POST["name"]) ? $_POST["name"] : null;
    $player->level = isset($_POST["level"]) ? $_POST["level"] : null;
    $player->strength = isset($_POST["strength"]) ? $_POST["strength"] : null;
    $player->speed = isset($_POST["speed"]) ? $_POST["speed"] : null;
    $player->reaction_time = isset($_POST["reaction_time"]) ? $_POST["reaction_time"] : null;

    return TennisRegistration::register($tournament_id, $player);
});

dispatch_post("/tournament/fast/", function() {
    $players = [];

    if(!isset($_POST["players"]) || !isset($_POST["name"]) || !isset($_POST["gender"])) {
        return "{\"status\": " . HTTP_BAD_REQUEST . "}";
    }

    $players = [];
    foreach (json_decode($_POST["players"]) as $player) {
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
    $tournament->name = $_POST["name"];
    $tournament->gender = $_POST["gender"];

    return TennisTournament::fast_tournament($players, $tournament);
});

dispatch_get("/tournament/search/", function() {
    $min_date = isset($_GET["min_date"]) ? $_GET["min_date"] : null;
    $max_date = isset($_GET["max_date"]) ? $_GET["max_date"] : null;
    $gender = isset($_GET["gender"]) ? $_GET["gender"] : null;
    $disputed = isset($_GET["disputed"]) ? $_GET["disputed"] : null;

    return TennisTournament::search_tournament([
        "min_date" => $min_date,
        "max_date" => $max_date,
        "gender" => $gender,
        "disputed" => $disputed,
    ]);
});