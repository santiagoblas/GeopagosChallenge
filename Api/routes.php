<?php

namespace Api;

use stdClass;

function hello()
{
    return 'Hello world!';
}

dispatch('/', 'Api\\hello');

dispatch("/Api/player", "Api\\hello");

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