<?php

namespace Tennis;

use DateTime;

class TennisTournament {
    private int $_id;
    private string $name;
    private array $players;
    private bool $disputed;
    private string $date;
    private TennisPlayer $winner;

    public function __construct(string $name, array $players = [], bool $disputed = false, string $date = null, TennisPlayer $winner = null)
    {
        $this->_id = 0;
        $this->name = $name;

        $players = array_filter($players, function($player) {
            if ($player instanceof TennisPlayer) {
                return true;
            }
            return false;
        });
        $this->players = $players;

        $this->disputed = $disputed;

        if ($date != null) {
            $this->date = $date;
        }

        if ($winner != null) {
            $this->winner = $winner;
        }
    }

    public function dispute($players_to_compete = []) : TennisPlayer {
        if (count($players_to_compete) == 0) {
            $players_to_compete = $this->players;
            shuffle($players_to_compete);
        }

        $n_rounds = (int)log(count($players_to_compete), 2);
        $next_round_players = $players_to_compete;

        for ($i=0; $i < $n_rounds; $i++) { 
            $next_round_players = $this->dispute_round($next_round_players);
        }

        $winner = $next_round_players[0];

        return $winner;
    }

    public function can_dispute() {
        $amount = count($this->players);
        $is_power_of_2 = ( $amount > 0 ) && ( ( $amount & ( $amount - 1 ) ) == 0 );

        return $is_power_of_2;
    }

    public function dispute_round($players_to_compete) : array {
        $round_winners = [];

        for ($i = 0; $i < count($players_to_compete) - 1; $i += 2) { 
            $player_a = $players_to_compete[$i];
            $player_b = $players_to_compete[$i + 1];

            $match = $this->get_tennis_match($player_a, $player_b);

            $round_winners[] = $match->dispute();
        }

        return $round_winners;
    }

    private function get_tennis_match(TennisPlayer $player_a, TennisPlayer $player_b) : TennisMatch {
        $match = new TennisMatch($player_a, $player_b);

        return $match;
    }

    public function get_id() : string {
        return $this->_id;
    }

    public function set_id(int $id) {
        if ($this->_id != 0) {
            return;
        }

        $this->_id = $id;
    }

    public function get_name() : string {
        return $this->name;
    }

    public function get_players() : array {
        return $this->players;
    }

    public function get_disputed() : bool {
        return $this->disputed;
    }

    public function set_disputed(bool $disputed) : void {
        $this->disputed = $disputed;
    }

    public function get_date() : string {
        return $this->date;
    }

    public function set_date($date) : void {
        $this->date = $date;
    }

    public function get_winner() : ?TennisPlayer {
        return $this->winner;
    }

    public function set_winner($winner) : void {
        $this->winner = $winner;
    }

    public function add_player(TennisPlayer $player) {
        $this->players[] = $player;
    }
}