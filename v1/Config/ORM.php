<?php

namespace Backend\Config;

use Backend\Config\Database;
use Backend\Utils\Helper;

class ORM extends Database {
     protected $db;
     public $table = NULL;
     public $where = ['cond' => '', 'value' => []],
            $update = [''],
            $join;

     private function connectDB(){
          $this->db = new Database;
     }
     
     public function all(Array $select = null){
          $this->connectDB();
          if($select != null){
               $field = implode(", ", $select);
               $this->db->query("SELECT $field FROM $this->table");
          }else{
               $this->db->query("SELECT * FROM $this->table");
          }
          return $this->db->getAll();
     }

     public function where(Array $cond){
          if(is_array($cond[0])){
               foreach($cond as $key => $val){
                    if($key != count($cond) - 1){
                         $this->where['cond'] .= $val[0].$val[1].':'.$val[0].' AND ';
                    }else{
                         $this->where['cond'] .= $val[0].$val[1].':'.$val[0];
                    }
                    $this->where['value'][$val[0]] = $val[2];
               }
          }else{
               $this->where['cond'] = $cond[0].$cond[1].':'.$cond[0];
               $this->where['value'][(String)$cond[0]] = $cond[2];
          }
          return $this;
     }
     
     public function get(){
          $this->connectDB();
          $bind = $this->where['value'];
          $query = "SELECT * FROM $this->table WHERE ".$this->where['cond'];
          $this->db->query($query);
          foreach($bind as $key => $val){
               $this->db->bind($key, $val);
          }
          return $this->db->getAll();         
     }

     public function create(Array $data){
          $this->connectDB();
          $field = implode(", ", array_keys($data));
          $val = Helper::arrayKeyToBind($data);
          $this->db->query("INSERT INTO $this->table ($field) VALUES($val)");
          foreach($data as $key => $value){
               $this->db->bind($key, $value);
          }
          $this->db->execute();
     }

     public function delete(){
          $this->connectDB();
          $bind = $this->where['value'];
          $query = "DELETE FROM $this->table WHERE ".$this->where['cond'];
          $this->db->query($query);
          foreach($bind as $key => $val){
               $this->db->bind($key, $val);
          }
          return $this->db->execute();
     }

     public function update(Array $data){
          $this->connectDB();
          unset($data['id']);
          $bind = '';
          $val = [];
          foreach($data as $key => $val){
               if(end($data) == $val){
                    $bind .= $key.'=:'.$key;
               }else{
                    $bind .= $key.'=:'.$key.', ';
               }
          }
          $query = "UPDATE $this->table SET $bind WHERE ".$this->where['cond'];
          $this->db->query($query);
          foreach($data as $key => $val){
               $this->db->bind($key, $val);
          }
          foreach($this->where['value'] as $key => $val){
               $this->db->bind($key, $val);
          }
          return $this->db->execute();
     }
}