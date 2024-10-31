<?php
namespace Data;
require 'vendor/autoload.php';

use LessQL\Row;
use Tennis\TennisPlayer as TennisTennisPlayer;

class TennisPlayer implements Model {
    private TennisTennisPlayer $player;

    public function __construct(TennisTennisPlayer $player = null)
    {
        $this->player = $player;
    }

    public function find(int $id) : TennisTennisPlayer {
        $connection = new MysqlConnection();
        $db = $connection->get_connection();

        $result = $db->users()->where('id', $id)->fetch();

        return $result;
    }

    public function save() : Row {
        if (!$this->validate()) {
            return null;
        }

        $connection = new MysqlConnection();
        $db = $connection->get_connection();

        $tennis_player = $db->createRow('tennis_players', [
            'name' => $this->player->get_name(), 
            'level' => $this->player->get_level(),
            'strength' => 8,
            'speed' => 8,
            'reaction_time' => 0.5
        ]);

        $tennis_player->save();

        return $tennis_player;
    }

    public function delete() {

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