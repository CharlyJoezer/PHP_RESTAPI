<?php

namespace Backend\Config;

use Backend\Config\Database;
use Backend\Utils\Helper;

class ORM extends Database {
     protected $db;
     public $table = NULL;
     public $where,
             $join;

     private function connectDB(){
          $this->db = new Database;
     }

     public function where(Array $cond){
          if(is_array($cond[0])){
               $str = '';
               foreach($cond as $key => $item){
                    if($key != (count($cond) - 1)){
                         $str .= $item[0].$item[1].$item[2].',';
                    }else{
                         $str .= $item[0].$item[1].$item[2];
                    }
               }
               $this->where = $str;
          }else{
               $str = $cond[0].$cond[1].$cond[2];
               $this->where = $str;
          }
          return $this;
     }
     
     public function get(){
          $this->connectDB();
          $this->db->query("SELECT * FROM $this->table WHERE $this->where");
          return $this->db->getAll();         
     }
}