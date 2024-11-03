<?php

namespace Tennis;

class TennisRegistration {
    private int $_id;
    private TennisPlayer $player;
    private TennisTournament $tournament;

    public function __construct(TennisPlayer $player, TennisTournament $tournament) {
        $this->_id = 0;
        $this->player = $player;
        $this->tournament = $tournament;
    }

    public function set_id(int $id) {
        if (is_null($this->_id)) {
            return;
        }

        $this->_id = $id;
    }
 }