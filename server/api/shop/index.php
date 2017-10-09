<?php
error_reporting(E_ALL);

require_once 'config.php';

spl_autoload_register(function($className){
    $file = "{$className}.php";
    if(file_exists("Library/$file")){
        require_once "Library/$file";
    }else if(file_exists("Controller/$file")){
        require_once "Controller/$file";
    }else if(file_exists("Model/$file")){
        require_once "Model/$file";
    }else{
         throw new \Exception("{$file} not found".PHP_EOL.__FILE__.PHP_EOL."- in ".__LINE__.PHP_EOL);
    }
});

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Controll-Allow-Headers: Authorization, Content-Type");

$obj = new RestServer();



