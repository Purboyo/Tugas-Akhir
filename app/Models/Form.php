<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['title', 'lab_id'];

    public function questions()
    {
        return $this->hasMany(Form_question::class);
    }

    public function lab()
    {
        return $this->belongsTo(Laboratory::class, 'lab_id');
    }
}

