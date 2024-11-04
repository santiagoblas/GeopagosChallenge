<?php
namespace Data;

use LessQL\Row;
use Tennis\FemaleTennisTournament;
use Tennis\MaleTennisTournament;
use Tennis\TennisTournament as TennisTennisTournament;

class TennisTournament extends Model {
    public TennisTennisTournament $tournament;

    public function __construct(TennisTennisTournament $tournament = null)
    {
        $connection = new MysqlConnection();
        $this->db = $connection->get_connection();
        $this->table_name = 'tennis_tournaments';

        if ($tournament == null) {
            return;
        }
        
        $this->tournament = $tournament;
    }

    public function set_tournament(TennisTennisTournament $tournament) {
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

        $tennis_tournament->set_id($result["id"]);
        $tennis_tournament->set_disputed($result["disputed"]);

        if (!is_null($result["date"])) {
            $tennis_tournament->set_date($result["date"]);
        }

        $this->tournament = $tennis_tournament;

        $data_tennis_registration = new TennisRegistration();
        $tennis_registrations = $data_tennis_registration->find_all_by_tournament($tennis_tournament);
        foreach ($tennis_registrations as $tennis_registration) {
            $tennis_tournament->add_player($tennis_registration->get_player());

            if ($tennis_registration->get_win()) {
                $tennis_tournament->set_winner($tennis_registration->get_player());
            }
        }

        return $tennis_tournament;
    }

    public function search($params) : array {
        $table_name = $this->table_name;

        $query = $this->db->table($table_name);

        if (!empty($params["min_date"])) {
            $query = $query->where('date >= ?', $params["min_date"]);
        }

        if (!empty($params["max_date"])) {
            $query = $query->where('date <= ?', $params["max_date"]);
        }

        if (!empty($params["gender"])) {
            $query = $query->where('gender = ?', $params["gender"]);
        }

        if (isset($params["disputed"])) {
            $query = $query->where('disputed = ?', $params["disputed"]);
        }

        $results = $query->fetchAll();

        $tournaments = [];
        foreach ($results as $result) {
            $tournaments[] = $this->build_tennis_tournament($result);
        }

        return $tournaments;
    }

    private function build_tennis_tournament($result) {
        if ($result['gender'] == "M") {
            $tennis_tournament = new MaleTennisTournament($result['name']);
        } else {
            $tennis_tournament = new FemaleTennisTournament($result['name']);
        }

        $tennis_tournament->set_id($result["id"]);
        $tennis_tournament->set_disputed($result["disputed"]);

        if (!is_null($result["date"])) {
            $tennis_tournament->set_date($result["date"]);
        }

        $this->tournament = $tennis_tournament;

        $data_tennis_registration = new TennisRegistration();
        $tennis_registrations = $data_tennis_registration->find_all_by_tournament($tennis_tournament);
        foreach ($tennis_registrations as $tennis_registration) {
            $tennis_tournament->add_player($tennis_registration->get_player());

            if ($tennis_registration->get_win()) {
                $tennis_tournament->set_winner($tennis_registration->get_player());
            }
        }

        return $tennis_tournament;
    }

    public function save() : ?Row {
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

        $tennis_tournament = $this->db->createRow($this->table_name, $tennis_tournament);

        $tennis_tournament->save();

        return $tennis_tournament;
    }

    public function update() : ?Row {
        if (!$this->validate()) {
            return null;
        }

        $tennis_tournament = [
            'name' => $this->tournament->get_name(),
            'gender' => $this->tournament instanceof MaleTennisTournament ? "M" : "F",
            'disputed' => $this->tournament->get_disputed()
        ];

        if ($tennis_tournament["disputed"]) {
            $tennis_tournament["date"] = $this->tournament->get_date();
        }

        $table_name = $this->table_name;
        $result = $this->db->$table_name()->where('id', $this->tournament->get_id());

        if (is_null($result)) {
            return null;
        }

        $result->update($tennis_tournament);

        return $result->fetch();
    }

    public function validate() : bool {
        $valid = true;

        if ($this->tournament->get_name() == "") {
            $valid = false;
        }

        return $valid;
    }
}