<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewTemplates extends Model
{
    use HasFactory;
    protected $table = 'view_templates';
    public $timestamps = false;
}
