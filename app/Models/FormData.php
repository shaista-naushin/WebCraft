<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    use HasFactory;
    protected $fillable = [];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
