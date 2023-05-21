<?php

namespace Backend\Controllers;

use Backend\Models\Product;
use Backend\Utils\Validator;
use Backend\Utils\Helper;
use Exception;

class ProductController {
     public function getAllDataProduct(){
          $prd = new Product;
          $getData = $prd->select();
          return Helper::response(200, [
               'status' => true,
               'code' => 200,
               'message' => 'Get Data Product',
               'data' => [
                    'product' => $getData
               ]
          ]);
     }

     public function insert(){
          Validator::validate([
               'name' => ['required'],
               'price' => ['required','numeric']
          ]);

          $prd = new Product;
          try{
               $prd->insert([
                    'name' => $_POST['name'],
                    'price'=> $_POST['price']
               ]);
          }catch(Exception $e){
               return Helper::response(500, [
                    'status' => false,
                    'code' => 500,
                    'message' => 'Server Error 500'
               ]);
          }
          
          return Helper::response(200, [
               'status' => true,
               'code' => 200,
               'message' => 'Product Create Success!'
          ]);
     }
}