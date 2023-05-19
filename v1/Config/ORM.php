<?php

namespace Backend\Config;

use Backend\Config\Database;
use Backend\Utils\Helper;

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

     public function insert($data){
          $this->connectDB();
          $query = "INSERT INTO $this->table (". (string)implode(', ', array_keys($data)) . ") VALUES (".Helper::arrayKeyToBind($data).")";
          $this->db->query($query);
          for($i = 0; $i < count($data); $i++){
               $key = (string)array_keys($data)[$i];
               $this->db->bind($key, $data[$key]);
          }
          $this->db->execute();
          return true;
     }
}