<?php
namespace Data;

use LessQL\Database;
use LessQL\Row;
use Tennis\TennisPlayer as TennisTennisPlayer;

class TennisPlayer extends Model {
    public TennisTennisPlayer $player;

    public function __construct(TennisTennisPlayer $player = null)
    {
        $connection = new MysqlConnection();
        $this->db = $connection->get_connection();
        $this->table_name = 'tennis_players';

        if ($player == null) {
            return;
        }

        $this->player = $player;
    }

    public function find(int $id) : TennisTennisPlayer {
        $table_name = $this->table_name;
        $result = $this->db->$table_name()->where('id', $id)->fetch();
        
        if (is_null($result)) {
            return null;
        }

        $tennis_player = new TennisTennisPlayer(
            $result['name'],
            $result['level'],
            $result['strength'],
            $result['speed'],
            $result['reaction_time']
        );

        $tennis_player->set_id($id);

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

        $tennis_player = $this->db->createRow($this->table_name, $tennis_player);

        $tennis_player->save();

        return $tennis_player;
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