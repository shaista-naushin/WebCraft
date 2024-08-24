<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormData extends Model
{
    protected $fillable = [];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
