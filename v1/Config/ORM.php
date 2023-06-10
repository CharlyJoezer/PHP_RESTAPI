<?php

namespace Backend\Config;

use Backend\Config\Database;
use Backend\Utils\Helper;

class ORM extends Database {
     protected $db;
     public $table = NULL;
     protected $where = ['cond' => '', 'value' => []],
            $update = [''],
            $join = [];

     private function connectDB(){
          $this->db = new Database;
     }
     
     public function all(Array $select = []){
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
          $filter = str_replace(['.', '_'], '', $cond[0]);
          if(is_array($cond[0])){
               foreach($cond as $key => $val){
                    $filter = str_replace(['.', '_'], '', $val[0]);
                    if($key != count($cond) - 1){
                         $this->where['cond'] .= $val[0].$val[1].':'.$filter.' AND ';
                    }else{
                         $this->where['cond'] .= $val[0].$val[1].':'.$filter;
                    }
                    $this->where['value'][$filter] = $val[2];
               }
          }else{
               $this->where['cond'] = $cond[0].$cond[1].':'.$filter;
               $this->where['value'][(String)$filter] = $cond[2];
          }
          return $this;
     }
     
     public function get(Array $select = []){
          $this->connectDB();
          $where = $this->where['value'];
          if(count($select) > 0){
               (String) $strSelect = implode(', ', $select);
               $query = "SELECT $strSelect FROM $this->table";
          }else{
               $query = "SELECT * FROM $this->table";
          }
          if(count($this->join) > 0){
               $query .= ' '.$this->join['type']." JOIN ".$this->join['table']." ON ".$this->join['or'];
          }
          if(count($where) > 0){
               $query .= ' WHERE '.$this->where['cond'];
               $this->db->query($query);
               foreach($where as $key => $val){
                    $this->db->bind($key, $val);
               }
          }else{
               $this->db->query($query);
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
          return $this->db->execute();
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

     public function join(String $table, String $type, String $or){
          $this->join['table'] = $table;
          $this->join['type'] = $type;
          $this->join['or'] = $or;
          return $this;
     }
}