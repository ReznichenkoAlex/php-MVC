<?php

use App\Controller\Blog;
use App\Controller\User;
use Base\Application;
use Base\Route;
use Base\RouteException;

require_once './vendor/autoload.php';
require_once './src/config.php';

if(!file_exists('./images')){
    mkdir('./images');
}

$app = new Application();
$app->run();




