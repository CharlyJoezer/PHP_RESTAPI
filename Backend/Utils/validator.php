<?php
namespace Backend\Utils;
use Backend\Utils\Helper;

class Validator {
     public static function validate(Array $data){
          (Array) $error = [];
          foreach($data as $key => $val){
               $obj = new self();
               $invalid = $obj->helperValidate($val, $key);
               if(isset($invalid)){
                    $error[$key] = $invalid;
               }
          }

          if(count($error) > 0){
               return Helper::response(422, [
                    'errors' => $error,
                    'message' => 'Input is not Validated',
                    'code' => 422
               ]);
          }else{
               return true;
          }


     }
     protected function helperValidate($cond, $key){
          $data = Request::Input();
          foreach($cond as $val){
               if('required' == $val){
                    if( empty($data[(String) $key]) ){
                         return $key . ' is required';
                    }
               }
               if('numeric' == $val){
                    if( !is_numeric($data[(String) $key]) ){
                         return $key . ' must be a number';
                    }
               }
               if(stripos($val, 'min:') !== false){
                    $str = substr($val, stripos($val, ':')+1);
                    if(strlen($data[(String) $key]) < (Int)$str){
                         return $key . " minimum $str character for correct!";
                    }
               }
               if(stripos($val, 'max:') !== false){
                    $str = substr($val, stripos($val, ':')+1);
                    if(strlen($data[(String) $key]) > (Int)$str){
                         return $key . " maximum $str character for correct!";
                    }
               }
          }
     }
}