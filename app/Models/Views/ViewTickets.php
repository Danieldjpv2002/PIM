<?php

namespace App\Models\Views;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewTickets extends Model
{
    use HasFactory;
    protected $table = 'view_tickets';
    public $timestamps = false;
}
