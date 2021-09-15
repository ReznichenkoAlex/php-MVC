<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'eloquent',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);


$capsule->setAsGlobal();
$capsule->bootEloquent();

$capsule->getConnection()->enableQueryLog();

function printLog()
{
    echo '<pre>';
    $log = Capsule::connection()->getQueryLog();
    foreach ($log as $elem) {
        echo 0.01 * $elem['time'] . ': ' . $elem['query'] . ' bind: ' . json_encode($elem['bindings']) . '<br>';
    }

    echo '</pre>';
}