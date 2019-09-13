<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $timestamps = false;

    protected $table = 'users';
    protected $fillable = [
        'email', 'phone', 'address',
    ];
}
