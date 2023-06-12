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

    public function updateAmountProduct(){
        $token = Tokens::tokenValidation();
        Validator::validate([
            'product' => ['required', 'numeric'],
            'operator' => ['required']
        ]);
        $data = Request::input();
        $krj = new Keranjang;
        $getData = $krj->where([
            ['product_id', '=', $data['product']],
            ['user_id', '=', $token['user_id']]
        ])->get();
        if(count($getData) > 0){
            // CHECK IS PLUS OR MIN AMOUNT PRODUCT
            if($data['operator'] == 'min'){
                //CHECK the product amount is 1 
                if($getData[0]['amount'] == 1){
                    return Helper::response(403, [
                        'status' => false,
                        'message' => 'Minimum number of product is 1'
                    ]);
                }
                // -------------------
                $queryUpdate = ['amount' => (Int)$getData[0]['amount'] - 1];
            }else if($data['operator'] == 'plus'){
                $queryUpdate = ['amount' => (Int)$getData[0]['amount'] + 1];
            }else{
                // RESPONSE IF NOT 1 TRUE
                return Helper::response(403, [
                    'status' => false,
                    'message' => 'Forbidden'
                ]);
            }
            // ---------------

            // UPDATE PRODUCT
            $krjUpdate = new Keranjang;
            $updateData = $krjUpdate->where([
                ['product_id', '=', $data['product']],
                ['user_id', '=', $token['user_id']]
            ])->update($queryUpdate);
            // -------------------------

            // RESPONSE
            if($updateData){
                return Helper::response(200, [
                    'status' => true,
                    'message' => 'Update Success'
                ]);
            }else{
                return Helper::response(500, [
                    'status' => false,
                    'message' => 'Server Error'
                ]);
            }
        }

        // RETURN Forbidden if is not user product or product not found
        return Helper::response(403, [
            'status' => false,
            'message' => 'Forbidden'
        ]);
    }

    public function deleteProductKeranjang(){
        $token = Tokens::tokenValidation();
        Validator::validate([
            'product' => ['required', 'numeric']
        ]);
        $data = Request::input();
        $krj = new Keranjang;
        $getData = $krj->where([
            ['product_id', '=', $data['product']],
            ['user_id', '=', $token['user_id']]
        ])->get();
        if(count($getData) > 0){
            $delete = new Keranjang;
            $deleteAction = $delete->where([
                ['product_id', '=', $getData[0]['product_id']],
                ['user_id', '=', $token['user_id']]
            ])->delete();
            if($deleteAction){
                return Helper::response(200, [
                    'status' => true,
                    'messsage' => '1 Product was deleted from the cart!'
                ]);
            }else{
                return Helper::response(500, [
                    'status' => false,
                    'messsage' => 'SERVER ERROR!'
                ]);

            }
        }
        // RETURN Forbidden if is not user product or product not found
        return Helper::response(403, [
            'status' => false,
            'message' => 'Forbidden'
        ]);
    }
}