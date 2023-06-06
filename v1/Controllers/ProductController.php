<?php

namespace Backend\Controllers;

use Backend\Models\Product;
use Backend\Utils\Validator;
use Backend\Utils\Helper;
use Backend\Utils\Request;
use Exception;

class ProductController {
     public function getOneProduct(){
          $data = Request::input();
          Validator::validate([
               'id' => ['required','numeric']
          ]);
          $prd = new Product();
          $getData = $prd->where(['id_product', '=', $data['id']])->get();
          if(count($getData) > 0){
               Helper::response(200, [
                    'code' => 200,
                    'status' => true,
                    'message' => 'Success, Data is found!',
                    'data' => $getData
               ]);
          }else{
               Helper::response(404, [
                    'code' => 404,
                    'status' => false,
                    'message' => 'Data not found!'
               ]);

          }
     }

     public function getAll(){
          $prd = new Product;
          $data = $prd->all();
          if(count($data) > 0){
               Helper::response(200, [
                    'code' => 200,
                    'status' => true,
                    'message' => 'Success, Data is found!',
                    'data' => $data
               ]);
          }else{
               Helper::response(404, [
                    'code' => 404,
                    'status' => false,
                    'message' => 'Data not found!'
               ]);

          }
     }

     public function createProduct(){
          $data = Request::input();
          Validator::validate([
               'name' => ['required'],
               'price' => ['required', 'price']
          ]);
          $prd = new Product;
          $prd->create($data);
          return Helper::response(201, [
               'code' => 201,
               'status' => true,
               'message' => 'Success Created Product!',
          ]);
     }

     public function update(){
          $data = Request::input();
          Validator::validate([
               'name' => ['required'],
               'price' => ['required', 'numeric'],
               'id' => ['required', 'numeric']
          ]);
          $finalData = [
               'name' => $data['name'],
               'price' => (Int)$data['price']
          ];
          $prd = new Product;
          $update = $prd->where(['id_product', '=', $data['id']])->update($finalData);
          if($update){
               return Helper::response(200, [
                    'code' => 200,
                    'status' => true,
                    'message' => 'Success, Data is Modify',
               ]);
          }else{
               return Helper::response(500, [
                    'code' => 500,
                    'status' => false,
                    'message' => 'Server Error'
               ]);
          }
     }
}