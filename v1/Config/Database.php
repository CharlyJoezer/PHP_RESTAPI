<?php

class Database {
     private $db_host = 'localhost';
     private $db_name = 'php_restapi';
     private $db_user = 'root';
     private $db_pass = '';

     public function __construct(){
          try{
               $conn = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_pass);
               $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          }catch(Exception $e){
               echo $e->getMessage();
          }
     }
     
}