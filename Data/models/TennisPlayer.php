<?php
namespace Data;

use LessQL\Database;
use LessQL\Row;
use Tennis\TennisPlayer as TennisTennisPlayer;

class TennisPlayer implements Model {
    private Database $db;
    private TennisTennisPlayer $player;

    public function __construct(DatabaseConnection $connection, TennisTennisPlayer $player = null)
    {
        $this->db = $connection->get_connection();

        if ($player == null) {
            return;
        }

        $this->player = $player;
    }

    public function find(int $id) : TennisTennisPlayer {
        $result = $this->db->users()->where('id', $id)->fetch();

        $tennis_player = new TennisTennisPlayer(
            $result['name'],
            $result['level'],
            $result['strength'],
            $result['speed'],
            $result['reaction_time']
        );

        $this->player = $tennis_player;

        return $tennis_player;
    }

    public function save() : Row {
        if (!$this->validate()) {
            return null;
        }

        $tennis_player = [
            'name' => $this->player->get_name(), 
            'level' => $this->player->get_level(),
            'strength' => $this->player->get_strength(),
            'speed' => $this->player->get_speed(),
            'reaction_time' => $this->player->get_reaction_time()
        ];

        if ($this->player->get_id() != 0) {
            $tennis_player['id'] = $this->player->get_id();
        }

        $tennis_player = $this->db->createRow('tennis_players', $tennis_player);

        $tennis_player->save();

        return $tennis_player;
    }

    public function delete(int $id) : bool {
        $tennis_player = $this->db->tennis_players()->where('id', $id);

        if (is_null($tennis_player)) {
            return false;
        }
        
        $rows = $tennis_player->delete()->rowCount();

        if ($rows > 0) {
            return true;
        }

        return false;
    }

    public function validate() : bool {
        $valid = true;

        if ($this->player->get_name() == "") {
            $valid = false;
        }

        $level = $this->player->get_level();
        if ($level < 0 || $level > 100) {
            $valid = false;
        }

        return $valid;
    }
}