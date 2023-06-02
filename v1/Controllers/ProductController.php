<?php

namespace Backend\Controllers;

use Backend\Models\Product;
use Backend\Utils\Validator;
use Backend\Utils\Helper;
use Backend\Utils\Request;
use Exception;

class ProductController {

     public function read(){
          $prd = new Product();
          Helper::response(200, $prd->where(['price', '<', '12000'])->get());
     }
}