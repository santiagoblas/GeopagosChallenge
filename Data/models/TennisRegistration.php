<?php

namespace Data;

use LessQL\Database;
use LessQL\Row;
use Tennis\TennisRegistration as TennisTennisRegistration;
use Tennis\TennisTournament as TennisTennisTournament;

class TennisRegistration extends Model {
    public TennisTennisRegistration $registration;

    public function __construct(TennisTennisRegistration $registration = null)
    {
        $connection = new MysqlConnection();
        $this->db = $connection->get_connection();
        $this->table_name = 'tennis_players_tennis_tournaments';

        if ($registration== null) {
            return;
        }
        
        $this->registration = $registration;
    }

    public function find(int $id) : ?TennisTennisRegistration {
        $tennis_player = new TennisPlayer();
        $tennis_tournament = new TennisTournament();

        $table_name = $this->table_name;
        $result = $this->db->$table_name()->where('id', $id)->fetch();

        if (is_null($result)) {
            return null;
        }

        $tennis_player = $tennis_player->find($result["tennis_player_id"]);
        $tennis_tournament = $tennis_tournament->find($result["tennis_tournament_id"]);

        $tennis_registration = new TennisTennisRegistration($tennis_player, $tennis_tournament, $result["win"]);

        $tennis_registration->set_id($id);

        return $tennis_registration;
    }

    public function find_all_by_tournament(TennisTennisTournament $tournament) : array {
        $data_tennis_player = new TennisPlayer();

        $table_name = $this->table_name;
        $result = $this->db->$table_name()->where('tennis_tournament_id', $tournament->get_id())->fetchAll();

        if (is_null($result)) {
            return [];
        }

        $tennis_registrations = [];
        foreach ($result as $tennis_registration) {
            $tennis_player = $data_tennis_player->find($tennis_registration["tennis_player_id"]);
            $tennis_tennis_registration = new TennisTennisRegistration($tennis_player, $tournament, $tennis_registration["win"]);
            $tennis_tennis_registration->set_id($tennis_registration["id"]);
            $tennis_registrations[] = $tennis_tennis_registration;
        }

        return $tennis_registrations;
    }

    public function save() : Row {
        if (!$this->validate()) {
            return null;
        }

        $tennis_registration = [
            'tennis_tournament_id' => $this->registration->get_tournament()->get_id(),
            'tennis_player_id' => $this->registration->get_player()->get_id(),
        ];

        $tennis_registration = $this->db->createRow($this->table_name, $tennis_registration);

        $tennis_registration->save();

        return $tennis_registration;
    }

    public function update() : ?Row {
        if (!$this->validate()) {
            return null;
        }

        $tennis_registration = [
            'tennis_tournament_id' => $this->registration->get_tournament()->get_id(),
            'tennis_player_id' => $this->registration->get_player()->get_id(),
            'win' => $this->registration->get_win()
        ];

        $table_name = $this->table_name;
        $result = $this->db->$table_name()->where('id', $this->registration->get_id());

        if (is_null($result)) {
            return null;
        }

        $result->update($tennis_registration);

        return $result->fetch();
    }
}