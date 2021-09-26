<?php

namespace App\Model;
require_once 'initEloquent.php';

class PostEloquent extends \Illuminate\Database\Eloquent\Model
{
    public $table = 'posts';

    public function userdata()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}