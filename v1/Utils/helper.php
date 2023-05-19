<?php

namespace Backend\Utils;


class Helper {
     public static function Controller($class, $method, $data = null){
          return $class->$method();
     }

     public static function arrayKeyToBind($data){
          return (string)implode(', ',  array_map(function($key){
                                              return ":".$key;
                                        }, array_keys($data))
          );
     }

     public static function response(Int $code, Array $message = []){
          http_response_code($code);
          header('Content-Type: application/json');
          echo json_encode($message);
          exit();
     }
}