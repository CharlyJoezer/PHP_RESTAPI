<?php

namespace Backend\Routes;

class Route {
     private $path;
     private $data;
     private $method;

     public function __construct($url){
          $this->path = $url['path'];
          $this->method = $_SERVER['REQUEST_METHOD'];
          if(isset($url['query'])){
               $this->data = $url['query'];
          }
          $this->Route();
     }

     public function Route(){
          if($this->method == 'GET'){
               if($this->path == '/api/product/get'){
                    echo 'Get Data Product';
               }
          }else if($this->method == 'POST'){
               if($this->path == '/api/product/insert'){
                    echo 'Insert Data Product';
               }
          }else{
               $response = [
                    'message' => "Method Not Allowed",
                    'code' => "405"
               ];
               header('HTTP/1.1 405 Method Not Allowed');
               header('Content-Type: application/json');
               echo json_encode($response);
               die();
          }
     }
}