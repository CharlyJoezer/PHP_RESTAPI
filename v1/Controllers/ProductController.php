<?php

namespace Backend\Controllers;

use Backend\Models\Product;
use Backend\Utils\Validator;
use Backend\Utils\Helper;
use Backend\Utils\Request;
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
          $data = Request::Input();
          Validator::validate([
               'name' => ['required'],
               'price' => ['required','numeric']
          ]);
          
          $prd = new Product;
          try{
               $prd->insert([
                    'name' => $data['name'],
                    'price'=> $data['price']
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

     public function update(){
          $data = Request::Input();
          $prd = new Product;
          $prd->update("id_product = ".$data['id'], $data);
          return Helper::response(200, [
               'code' => 200,
               'status' => true,
               'message' => 'Data Succesfully Modify'
          ]);

     }

     public function delete(){
          $data = Request::input();
          Validator::validate([
               'id_product' => ['required','numeric']
          ]);
          $prd = new Product();
          $prd->delete("id_product", '=' ,$data['id_product']);
          return Helper::response(200, [
               'code' => 200,
               'status' => true,
               'message' => 'Delete Success!'
          ]);
     }
}