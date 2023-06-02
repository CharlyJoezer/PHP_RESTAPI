<?php

namespace Backend\Routes;
use Backend\Controllers\ProductController;
use Backend\Utils\Helper;

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
                    Helper::Controller(new ProductController, 'getAllDataProduct');
               }
          }else if($this->method == 'POST'){
               if($this->path == '/api/product/find'){
                    Helper::Controller(new ProductController, 'getOneProduct');
               }
               if($this->path == '/api/product/insert'){
                    Helper::Controller(new ProductController, 'insert');
               }
          }else if($this->method == 'PATCH'){
               if($this->path == '/api/product/update'){
                    Helper::Controller(new ProductController, 'update');
               }
          }else if($this->method == 'DELETE'){
               if($this->path == '/api/product/delete'){
                    Helper::Controller(new ProductController, 'delete');
               }
          }
     }
}