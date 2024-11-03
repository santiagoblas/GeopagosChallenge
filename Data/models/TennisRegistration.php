<?php

namespace Data;

use LessQL\Database;
use LessQL\Row;
use Tennis\TennisRegistration as TennisTennisRegistration;

class TennisRegistration extends Model {
    public int $tournament_id;
    public int $player_id;

    public function __construct(DatabaseConnection $connection, int $tournament_id = null, int $player_id = null)
    {
        $this->db = $connection->get_connection();
        $this->table_name = 'tennis_players_tennis_tournaments';

        if ($tournament_id == null || $player_id == null) {
            return;
        }
        
        $this->tournament_id = $tournament_id;
        $this->player_id = $player_id;
    }

    public function find(int $id) : ?TennisTennisRegistration {
        $tennis_player = new TennisPlayer(new MysqlConnection());
        $tennis_tournament = new TennisTournament(new MysqlConnection());

        $table_name = $this->table_name;
        $result = $this->db->$table_name()->where('id', $id)->fetch();

        if (is_null($result)) {
            return null;
        }

        $tennis_player = $tennis_player->find($result["tennis_tournament_id"]);
        $tennis_tournament = $tennis_tournament->find($result["tennis_player_id"]);

        $tennis_registration = new TennisTennisRegistration($tennis_player, $tennis_tournament);

        return $tennis_registration;
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