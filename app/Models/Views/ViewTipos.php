<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewTipos extends Model
{
    use HasFactory;
    protected $table = 'view_tipos';
    public $timestamps = false;
}
