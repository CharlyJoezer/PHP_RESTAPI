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
                    array_push($error, [$key => $invalid]);
               }
          }

          if(count($error) > 0){
               return Helper::response(400, [
                    'errors' => $error,
                    'message' => 'Input is not Validated',
                    'code' => 400
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
          }
     }
}