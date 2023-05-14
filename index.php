<?php 

require_once __DIR__. '/vendor/autoload.php';
use Backend\Config\Database;
use Backend\Routes\Route;

$conn = new Database();

$url = parse_url($_SERVER['REQUEST_URI']);
new Route($url);