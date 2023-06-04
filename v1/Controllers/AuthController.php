<?php
namespace Backend\Controllers;

use Backend\Models\User;
use Backend\Models\Token;
use Backend\Utils\Validator;
use Backend\Utils\Helper;
use Backend\Utils\Request;
use Exception;
class AuthController{
    public function login(){
        $data = Request::input();
        Validator::validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);
        $usr = new User;
        $checkUser = $usr->where([
            ['email', '=', $data['email']],
        ])->get();
        $userNotFound = Helper::response(404, [
            'code' => 404,
            'status' => false,
            'message' => 'Email atau Password Salah!'
        ]);
        if(count($checkUser) <= 0){
            return $userNotFound;
        }
        $checkPass = password_verify($data['password'], $checkUser[0]['password']);
        if($checkPass == true){
            $token = bin2hex(random_bytes(32));
            $data = [
                'token' => $token,
                'user_id' => $checkUser[0]['id_user']  
            ];
            $tkn = new Token;
            $tkn->create($data);
            return Helper::response(200, [
                'code' => 200,
                'status' => true,
                'message' => 'Login Success!',
                'data' => [
                    'token' => $token
                ]
            ]);
        }else{
            return $userNotFound;
        }
    }
}