<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['title', 'is_default', 'created_by'];

    public function questions()
    {
        return $this->hasMany(Form_question::class);
    }

    public function laboratories()
    {
        return $this->belongsToMany(Laboratory::class, 'form_laboratory');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}

