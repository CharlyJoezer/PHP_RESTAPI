<?php

namespace Backend\Config;

use Backend\Config\Database;

class ORM extends Database {
     protected $db;
     public $table = NULL;

     private function connectDB(){
          $this->db = new Database;
     }

     public function select(){
          $this->connectDB();
          $this->db->query("SELECT * FROM ". $this->table);
          return $this->db->getAll();
     }
}