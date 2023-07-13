<?php
namespace Backend\Utils;

class Request {
    public static function Input(){
        $type = isset($_SERVER['HTTP_CONTENT_TYPE']) ? $_SERVER['HTTP_CONTENT_TYPE'] : null;
        $data = [];
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $data = $_GET;
            return $data;
        }else{
            if($type == "application/json"){
                $input = json_decode(file_get_contents('php://input'), true);
                $data = $input;
                return $data;
            }
            else if($type == "application/x-www-form-urlencoded"){
                parse_str(file_get_contents('php://input'), $data);
                return $data;
            }
            else if(strpos($type, "multipart/form-data") !== false){
                $mergeRequest = array_merge($_POST, $_FILES);
                $data = $mergeRequest;
                return $data;
            }else{
                return Helper::response(415, [
                    'code' => 415,
                    'error' => 'Unsupported Media Type',
                    'message' => "Content-Type must be json,x-www-form-urlencoded, and multipart/form-data"
                ]);
            }
        }

    }
}