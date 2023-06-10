<?php
namespace Backend\Utils;

use Backend\Utils\Helper;
use Backend\Models\Token as modelToken;

class Token {
    public static function tokenValidation(){
        $token = $_SERVER['HTTP_AUTHORIZATION'];
        if(isset($token)){
            $tknModel = new modelToken;
            $getTokenDB = $tknModel->where(['token', '=', $token])->get();
            if(count($getTokenDB) > 0){
                return [
                    'user_id' => $getTokenDB[0]['user_id']
                ];
            }else{
                return Helper::response(401, [
                    'code' => 401,
                    'status' => false,
                    'message' => 'Your token is not match'
                ]);
            }
            Helper::response(200, $getTokenDB);
        }else{
            return Helper::response(401, [
                'code' => 401,
                'status' => false,
                'message' => 'Auth required, your token is not found!'
            ]);
        }
    }
}