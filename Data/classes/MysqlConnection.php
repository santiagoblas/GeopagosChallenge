<?php

namespace Data;

use LessQL\Database;
use PDO;

class MysqlConnection extends DatabaseConnection {
    const HOST = "127.0.0.1";
    const NAME = "mydb";
    const USER = "root";
    const PASS = "root";

    public function get_connection()
    {
        $this->pdo = new PDO("mysql:host=" . self::HOST . ";dbname=" . self::NAME, self::USER, self::PASS);
        $this->db = new Database($this->pdo);

        return $this->db;
    }
}   