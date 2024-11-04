<?php

namespace Data;

use LessQL\Database;
use LessQL\Row;
use PhpParser\Node\Expr\Cast\Object_;
use stdClass;

class Model {
    protected Database $db;
    protected string $table_name;
    
    public function find(int $id) : ?object { return new stdClass(); }
    public function save() : ?Row { return null; }
    public function update() : ?Row { return null; }

    public function delete(int $id) : bool {
        $table_name = $this->table_name;

        $row = $this->db->$table_name()->where('id', $id);
        
        if (is_null($row)) {
            return false;
        }
        
        $rows = $row->delete()->rowCount();

        if ($rows > 0) {
            return true;
        }

        return false;
    }

    public function validate() : bool {
        $valid = true;

        return $valid;
    }
}