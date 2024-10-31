<?php
namespace Data;

use LessQL\Database;
use LessQL\Row;
use Tennis\FemaleTennisTournament;
use Tennis\MaleTennisTournament;
use Tennis\TennisTournament as TennisTennisTournament;

class TennisTournament implements Model {
    private Database $db;
    private TennisTennisTournament $tournament;

    public function __construct(DatabaseConnection $connection, TennisTennisTournament $tournament = null)
    {
        $this->db = $connection->get_connection();

        if ($tournament == null) {
            return;
        }
        
        $this->tournament = $tournament;
    }

    public function find(int $id) : TennisTennisTournament {
        $result = $this->db->users()->where('id', $id)->fetch();

        if ($result['gender'] == "M") {
            $tennis_tournament = new MaleTennisTournament($result['name']);
        } else {
            $tennis_tournament = new FemaleTennisTournament($result['name']);
        }

        $this->tournament = $tennis_tournament;

        return $tennis_tournament;
    }

    public function save() : Row {
        if (!$this->validate()) {
            return null;
        }

        $tennis_tournament = [
            'name' => $this->tournament->get_name(),
            'gender' => $this->tournament instanceof MaleTennisTournament ? "M" : "F"
        ];

        if ($this->tournament->get_id() != 0) {
            $tennis_tournament['id'] = $this->tournament->get_id();
        }

        $tennis_tournament = $this->db->createRow('tennis_tournaments', $tennis_tournament);

        $tennis_tournament->save();

        return $tennis_tournament;
    }

    public function delete(int $id) : bool {
        $tennis_tournament = $this->db->tennis_tournaments()->where('id', $id);

        if (is_null($tennis_tournament)) {
            return false;
        }
        
        $rows = $tennis_tournament->delete()->rowCount();

        if ($rows > 0) {
            return true;
        }

        return false;
    }

    public function validate() : bool {
        $valid = true;

        if ($this->tournament->get_name() == "") {
            $valid = false;
        }

        return $valid;
    }
}