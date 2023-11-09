<?php

namespace App\Models\SoDe;

use Illuminate\Database\Eloquent\Model;

class ViewUsersByDedicated extends Model
{
    protected $connection = 'mysql_sode';
    protected $table = 'view_usersbydedicated';
    public $timestamps = false;
}
