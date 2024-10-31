<?php

namespace Data;

use Data\TennisPlayer;
use PHPUnit\Framework\TestCase;
use Tennis\TennisPlayer as TennisTennisPlayer;

class TennisPlayerTest extends TestCase {

    public function test_save_player() {
        $player = new TennisTennisPlayer("Lara", 88);

        $model = new TennisPlayer($player);

        $tennis_player = $model->save();

        $this->assertFalse(is_null($tennis_player), "Tennis player not saved");
    }

}