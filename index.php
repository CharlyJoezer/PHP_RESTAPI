<?php 
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');

require_once __DIR__. '/vendor/autoload.php';
use Backend\Config\Database;
use Backend\Routes\Route;

$conn = new Database();

$url = parse_url($_SERVER['REQUEST_URI']);
new Route($url);