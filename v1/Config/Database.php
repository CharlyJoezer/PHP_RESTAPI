<?php

namespace Backend\Config;
use PDO;
use Exception;
use Backend\Utils\Helper;

class Database {
     private $db_host = 'localhost';
     private $db_name = 'php_restapi';
     private $db_user = 'root';
     private $db_pass = '';
     private $db_port = '3306';
     private $db;
     private $stmt;

     public function __construct(){
          try{
               $conn = new PDO("mysql:host=$this->db_host;port=$this->db_port;dbname=$this->db_name", $this->db_user. $this->db_pass);
               $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               $this->db = $conn;
          }catch(Exception $e){
               echo  $e->getMessage();
          }
     }

     public function query($query){
        // try {
            $this->stmt = $this->db->prepare($query);
        // } catch (\Throwable $th) {
        //     return Helper::response(500, [
        //         'status' => false,
        //         'code' => 500,
        //         'message' => "SERVER ERROR 500"
        //     ]);
        // }
      }
      public function execute(){
          $this->stmt->execute();
      }
  
  
      public function getAll()
      {
          $this->execute();
          return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
      }
      public function single(){
          $this->execute();
          return $this->stmt->fetch(PDO::FETCH_ASSOC);
      }
      
      public function bind($param, $value, $type = null)
      {
          if( is_null($type)){
              switch(true) {
                  case is_int($value) :
                      $type = PDO::PARAM_INT;
                      break;
                  case is_bool($value) :
                      $type = PDO::PARAM_BOOL;
                      break;
                  case is_null($value) :
                      $type = PDO::PARAM_NULL;
                      break;
                  default :
                      $type = PDO::PARAM_STR;
              }
          }
  
          $this->stmt->bindValue($param, $value, $type);
      }
     
}