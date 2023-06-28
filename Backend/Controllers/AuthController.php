<?php
namespace Backend\Controllers;

use Backend\Models\User;
use Backend\Models\Token;
use Backend\Utils\Validator;
use Backend\Utils\Helper;
use Backend\Utils\Request;
use Backend\Utils\Token as Tokens;
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

        if(count($checkUser) <= 0){
            return Helper::response(404, [
                'code' => 404,
                'status' => false,
                'message' => 'Email atau Password Salah!'
            ]);
        }
        $checkPass = password_verify($data['password'], $checkUser[0]['password']);
        
        if($checkPass == true){
            $token = bin2hex(random_bytes(32));
            $data = [
                'token' => 'Bearer '.$token,
                'user_id' => $checkUser[0]['id_user']  
            ];
            $tkn = new Token;
            $tkn->create($data);
            return Helper::response(200, [
                'code' => 200,
                'status' => true,
                'message' => 'Login Success!',
                'data' => [
                    'token' => 'Bearer '.$token
                ]
            ]);
        }else{
            return Helper::response(404, [
                'code' => 404,
                'status' => false,
                'message' => 'Email atau Password Salah!'
            ]);
        }
    }

    public function logout(){
        $getToken = $_SERVER['HTTP_AUTHORIZATION'];
        $tkn = new Token;
        $checkToken = $tkn->where(['token', '=', $getToken])->get();
        if(count($checkToken) <= 0){
            return Helper::response(403, [
                'code' => 403,
                'status' => false,
                'message' => 'Login Required!'
            ]);
        }

        $delete = $tkn->where(['token', '=', $getToken])->delete();
        if($delete){
            return Helper::response(200, [
                'code' => 200,
                'status' => true,
                'message' => 'Logout Success!'
            ]);
        }else{
            return Helper::response(500, [
                'code' => 500,
                'status' => false,
                'message' => 'Server Error'
            ]);
        }
    }

    public function getDataUser(){
        $token = Tokens::tokenValidation();
        $usr = new User;
        $getData = $usr->where(['id_user', '=', $token['user_id']])->get(['name', 'email', 'created_at']);
        if(count($getData) > 0){
            return Helper::response(200, [
                'status' => true,
                'data' => $getData[0]
            ]);
        }else{
            return Helper::response(404, [
                'status' => false,
                'message' => 'User not found, Try to re-login'
            ]);
        }
    }
}