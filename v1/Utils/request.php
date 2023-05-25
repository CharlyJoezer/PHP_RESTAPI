<?php
namespace Backend\Utils;

class Request {
    public static function Input(){
        $data = [];
        parse_str(file_get_contents('php://input'), $data);
        return $data;
    }
}