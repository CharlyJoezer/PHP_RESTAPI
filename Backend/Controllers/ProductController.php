<?php

namespace Backend\Controllers;

use Backend\Models\Product;
use Backend\Models\User;
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
          
          // Validation and store image
          $image = Helper::storeImage('image', 'product');
          
          $finalData = [
               'name' => $data['name'],
               'price' => $data['price'],
               'path_image' => $image
          ];
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

     public function getUserProduct(){
          $data = Request::input();
          Validator::validate([
               'id_' => ['required', 'numeric']
          ]);
          
          $prd = new Product;
          $getPrd = $prd->where(['user_id', '=', $data['id_']])->get(['id_product AS number', 'name', 'price', 'created_at']);
          if(count($getPrd) > 0){
               return Helper::response(200, [
                    'code' => 200,
                    'status' => true,
                    'message' => 'Success, Found '.count($getPrd). ' Product',
                    'data' => $getPrd
               ]);
          }else{
               return Helper::response(404, [
                    'code' => 404,
                    'status' => false,
                    'message' => 'Fail, Product is not found!',
                    'data' => $getPrd
               ]);  
          }
     }

     public function searchProduct(){
          Validator::validate([
               'search' => ['required']
          ]);
          $data = Request::input();
          $prd = new Product;
          $getData = $prd->where(['name', ' LIKE ', '%'.$data['search'].'%'])->get();
          if(count($getData) > 0){
               return Helper::response(200, [
                    'status' => true,
                    'message' => 'Found '.count($getData). ' product!',
                    'count' => (Int)count($getData),
                    'data' => $getData
               ]);
          }else{
               return Helper::response(404, [
                    'status' => true,
                    'message' => 'Product is not found!',
                    'count' => (Int)count($getData),
                    'data' => $getData
               ]);
          }
     }
}