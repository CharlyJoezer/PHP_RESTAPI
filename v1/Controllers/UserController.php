<?php
namespace Backend\Controllers;

use Backend\Models\User;
use Backend\Utils\Validator;
use Backend\Utils\Helper;
use Backend\Utils\Request;
use Exception;
class UserController{
    public function register(){
        $data = Request::input();
        Validator::validate([
            'name' => ['required'],
            'password' => ['required'],
            'email' => ['required']
        ]);
        $usr = new User;
        $usr->create($data);
        return Helper::response(201, [
            'code' => 201,
            'status' => true,
            'message' => 'Register Success!',
        ]);
    }
}