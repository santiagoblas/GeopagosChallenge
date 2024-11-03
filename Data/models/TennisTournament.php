<?php
namespace Data;

use LessQL\Row;
use Tennis\FemaleTennisTournament;
use Tennis\MaleTennisTournament;
use Tennis\TennisTournament as TennisTennisTournament;

class TennisTournament extends Model {
    public TennisTennisTournament $tournament;

    public function __construct(DatabaseConnection $connection, TennisTennisTournament $tournament = null)
    {
        $this->db = $connection->get_connection();
        $this->table_name = 'tennis_tournaments';

        if ($tournament == null) {
            return;
        }
        
        $this->tournament = $tournament;
    }

    public function find(int $id) : ?TennisTennisTournament {
        $table_name = $this->table_name;
        $result = $this->db->$table_name()->where('id', $id)->fetch();

        if (is_null($result)) {
            return null;
        }

        if ($result['gender'] == "M") {
            $tennis_tournament = new MaleTennisTournament($result['name']);
        } else {
            $tennis_tournament = new FemaleTennisTournament($result['name']);
        }

        $tennis_tournament->set_id($id);

        $this->tournament = $tennis_tournament;

        return $tennis_tournament;
    }

    public function save() : Row {
        if (!$this->validate()) {
            return null;
        }

        $tennis_tournament = [
            'name' => $this->tournament->get_name(),
            'gender' => $this->tournament instanceof MaleTennisTournament ? "M" : "F",
        ];

        if ($this->tournament->get_id() != 0) {
            $tennis_tournament['id'] = $this->tournament->get_id();
        }

        $tennis_tournament = $this->db->createRow($this->table_name, $tennis_tournament);

        $tennis_tournament->save();

        return $tennis_tournament;
    }

    public function validate() : bool {
        $valid = true;

        if ($this->tournament->get_name() == "") {
            $valid = false;
        }

        return $valid;
    }
}