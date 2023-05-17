<?php

namespace Backend\Utils;


class Helper {
     public static function Controller($class, $method, $data = null){
          return $class->$method();
     }
}