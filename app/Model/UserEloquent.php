<?php

namespace App\Model;
require_once "initEloquent.php";

class UserEloquent extends \Illuminate\Database\Eloquent\Model
{
    public $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'email', 'password', 'avatar_image'];

    public function posts()
{
    return $this->hasMany('Post', 'user_id', 'id');
}

    public static function getPasswordHash(string $password): string
{
    return sha1('sadqwrwq' . $password);
}

}