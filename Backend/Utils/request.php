<?php
namespace Backend\Utils;

class Request {
    public static function Input(){
        $data = [];
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $data = $_GET;
        }else{
            parse_str(file_get_contents('php://input'), $data);
        }
        return $data;
    }
}