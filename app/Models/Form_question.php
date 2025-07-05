<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form_question extends Model
{

    protected $fillable = ['form_id', 'question_text', 'type', 'is_required', 'options','is_default'];
    protected $casts = [
        'options' => 'array', // supaya otomatis array saat diambil
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
