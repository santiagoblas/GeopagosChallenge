<?php

namespace Data;

use Data\TennisTournament;
use PHPUnit\Framework\TestCase;
use Tennis\TennisTournament as TennisTennisTournament;

class TennisTournamentTest extends TestCase {

    public function test_save_tournament() {
        $tournament = new TennisTennisTournament("test tournament", []);

        $model = new TennisTournament(new MysqlConnection(), $tournament);

        $tennis_tournament = $model->save();

        $id = $tennis_tournament->id;

        $this->assertFalse(is_null($id), "Tennis tournament not saved");

        return $id;
    }

    /**
     * @depends test_save_tournament
     * */
    public function test_delete_tournament($id) {
        $tennis_tournament = new TennisTournament(new MysqlConnection());

        $deleted = $tennis_tournament->delete($id);

        $this->assertTrue($deleted, "Delete affected 0 rows");
    }

}