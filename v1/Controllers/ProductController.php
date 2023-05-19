<?php

namespace Backend\Controllers;

use Backend\Models\Product;

class ProductController {
     public function getAllDataProduct(){
          $prd = new Product;
          $getData = $prd->select();
          var_dump($getData);
          die();
     }

     public function insert(){
          $request = [
               'name' => $_POST['name'],
               'price' => $_POST['price']
          ];

          $prd = new Product;
          echo $prd->insert($request);
     }
}