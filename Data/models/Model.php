<?php

namespace Data;

use LessQL\Row;

interface Model {
    public function find(int $id) : object;
    public function save() : Row;
    public function delete(int $id);
    public function validate() : bool;
}