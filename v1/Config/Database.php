<?php

namespace Backend\Config;
use PDO;
use Exception;

class Database {
     private $db_host = 'localhost';
     private $db_name = 'php_restapi';
     private $db_user = 'root';
     private $db_pass = '';
     private $db_port = '3308';

     public function __construct(){
          try{
               $conn = new PDO("mysql:host=$this->db_host;port=$this->db_port;dbname=$this->db_name", $this->db_user. $this->db_pass);
               $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          }catch(Exception $e){
               echo $e->getMessage();
          }
     }
     
}