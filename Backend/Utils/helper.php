<?php

namespace Backend\Utils;
use Backend\Utils\Token;

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

     public static function imageValidation($imgKey){
          if(isset($_FILES[$imgKey]) && is_array($_FILES[$imgKey])){
               $image = $_FILES[$imgKey];
               if($image['type'] != 'image/jpeg' AND 
                  $image['type'] != 'image/jpg' AND
                  $image['type'] != 'image/png'){
                    return Helper::response(400, [
                         'status' => false,
                         'message' => 'This file is not image type!'  
                    ]);
               }
               return $image;
          }else{
               return Helper::response(400, [
                    'status' => false,
                    'message' => 'Image Required'
               ]);
          }
     }

     public static function storeImage($imgKey, $storage){
          $image = self::imageValidation($imgKey);
          $imgLocal = $image['tmp_name'];
          $imgName = $image['name'];
          $imgExtension = pathinfo($imgName, PATHINFO_EXTENSION);
          $enkripName = $storage."_".md5($imgName).time().".$imgExtension";
          $pathStorage = "v1/Storage/$storage/".$enkripName;

          if(move_uploaded_file($imgLocal, $pathStorage)){
               return $enkripName;
          }else{
               return Helper::response(500, [
                    'status' => false,
                    'message'=> 'SERVER ERROR'
               ]); 
          }
     }
}