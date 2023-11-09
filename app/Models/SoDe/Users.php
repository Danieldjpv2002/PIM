<?php

namespace App\Models\SoDe;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $connection = 'mysql_sode';
    public $timestamps = false;
}
