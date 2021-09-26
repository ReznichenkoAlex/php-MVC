<?php
require_once 'initEloquent.php';

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('users', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->string('email');
    $table->string('password');
    $table->string('avatar_image')->nullable();
    $table->timestamps();
});

Capsule::schema()->create('posts', function (Blueprint $table) {
    $table->increments('id');
    $table->text('text');
    $table->integer('user_id')->unsigned();
    $table->string('user_name');
    $table->string('image')->nullable();
    $table->timestamps();
});

printLog();