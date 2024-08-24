<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    const TYPES = [
        'blank', 'landing', 'product', 'form', 'survey', 'email'
    ];

    protected $fillable = ['name'];
}
