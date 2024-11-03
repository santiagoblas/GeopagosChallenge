<?php

namespace Data;

use Data\TennisPlayer;
use PHPUnit\Framework\TestCase;
use Tennis\TennisPlayer as TennisTennisPlayer;

class TennisPlayerTest extends TestCase {

    public function test_save_player() {
        $player = new TennisTennisPlayer("Lara", 88, 50, 64, 0.4);

        $model = new TennisPlayer($player);

        $tennis_player = $model->save();

        $id = $tennis_player->id;

        $this->assertFalse(is_null($id), "Tennis player not saved");

        return $id;
    }

    /**
     * @depends test_save_player
     * */
    public function test_delete_player($id) {
        $tennis_player = new TennisPlayer();

        $deleted = $tennis_player->delete($id);

        $this->assertTrue($deleted, "Delete affected 0 rows");
    }

}