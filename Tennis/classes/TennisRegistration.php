<?php

namespace Tennis;

class TennisRegistration {
    private int $_id;
    private TennisPlayer $player;
    private TennisTournament $tournament;
    private bool $win;

    public function __construct(TennisPlayer $player, TennisTournament $tournament, bool $win = false) {
        $this->_id = 0;
        $this->player = $player;
        $this->tournament = $tournament;
        $this->win = $win;
    }

    public function set_id(int $id) {
        if ($this->_id != 0) {
            return;
        }

        $this->_id = $id;
    }

    public function get_id() {
        return $this->_id;
    }

    public function get_player() : TennisPlayer {
        return $this->player;
    }

    public function get_tournament() : TennisTournament {
        return $this->tournament;
    }

    public function get_win() : bool {
        return $this->win;
    }

    public function set_win($win) : void {
       $this->win = $win;
    }
 }