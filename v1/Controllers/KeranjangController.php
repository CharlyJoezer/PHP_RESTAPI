<?php
namespace Backend\Controllers;

use Backend\Models\Product;
use Backend\Models\User;
use Backend\Models\Token;
use Backend\Models\Keranjang;
use Backend\Utils\Validator;
use Backend\Utils\Helper;
use Backend\Utils\Request;
use Backend\Utils\Token as Tokens;
use Exception;

class KeranjangController{
    public function insertKeranjang(){
        $token = Tokens::tokenValidation();
        Validator::validate([
            'product' => ['required', 'numeric'],
            'amount' => ['required', 'numeric']
        ]);
        $data = Request::input();
        $insertData = [
            'user_id' => $token['user_id'],
            'product_id' => $data['product'],
            'amount' => $data['amount']
        ];
        $krj = new Keranjang;
        $checkAlready = $krj->where([
            ['product_id', '=', $insertData['product_id']],
            ['user_id', '=', $insertData['user_id']]
        ])->get();

        // CHECK THE PRODUCT WAS ALREADY ON THE CARD OR NOT
        if(count($checkAlready) > 0){
            $krjUpdate = new Keranjang;
            $query = $krjUpdate->where([
                ['product_id', '=', $checkAlready[0]['product_id']],
                ['user_id', '=', $checkAlready[0]['user_id']]
            ])->update(['amount' => (Int)$checkAlready[0]['amount'] + (Int)$data['amount']]);
            return Helper::response(200, [
                'status' => true,
                'message' => 'Success, The number of products has been added to the cart'
            ]);
        }

        // INSERT TO THE CART
        $query = $krj->create($insertData);

        // RETURN RESPONSE
        if($query){
            return Helper::response(201, [
                'status' => true,
                'message' => 'Success, The product has been added to the cart!'
            ]);
        }else{
            return Helper::response(500, [
                'status' => false,
                'message' => 'Failed, Server Error!'
            ]);
        }
    }

    public function getDataKeranjangUser(){
        $token = Tokens::tokenValidation();
        $krj = new Keranjang;
        $getData = $krj->where(['user_id', '=', $token['user_id']])->get();
        if(count($getData) > 0){
            return Helper::response(200, [
                'status' => true,
                'message' => 'Found '.count($getData).' product',
                'data' => $getData
            ]);
        }else{
            return Helper::response(200, [
                'status' => true,
                'message' => 'Found '.count($getData).' product',
                'data' => $getData
            ]);
        }
    }
}