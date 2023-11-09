<?php

namespace App\Models\SoDe\Views;

use Illuminate\Database\Eloquent\Model;

class ViewUsers extends Model
{
    protected $connection = 'mysql_sode';
    protected $table = 'view_users';
    public $timestamps = false;
}
