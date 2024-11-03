<?php

namespace Data;

use LessQL\Database;
use LessQL\Row;
use Tennis\TennisRegistration as TennisTennisRegistration;
use Tennis\TennisTournament as TennisTennisTournament;

class TennisRegistration extends Model {
    public int $tournament_id;
    public int $player_id;

    public function __construct(int $tournament_id = null, int $player_id = null)
    {
        $connection = new MysqlConnection();
        $this->db = $connection->get_connection();
        $this->table_name = 'tennis_players_tennis_tournaments';

        if ($tournament_id == null || $player_id == null) {
            return;
        }
        
        $this->tournament_id = $tournament_id;
        $this->player_id = $player_id;
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

        $tennis_registration = new TennisTennisRegistration($tennis_player, $tennis_tournament);

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
            $tennis_registration = new TennisTennisRegistration($tennis_player, $tournament);
            $tennis_registrations[] = $tennis_registration;
        }

        return $tennis_registrations;
    } 

    public function save() : Row {
        if (!$this->validate()) {
            return null;
        }

        $tennis_registration = [
            'tennis_tournament_id' => $this->tournament_id,
            'tennis_player_id' => $this->player_id,
        ];

        $tennis_registration = $this->db->createRow($this->table_name, $tennis_registration);

        $tennis_registration->save();

        return $tennis_registration;
    }
}