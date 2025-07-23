<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report_answer extends Model
{

    protected $fillable = ['report_id', 'question_id', 'answer_text', 'note'];

    public function report() {
        return $this->belongsTo(Report::class);
    }

    public function question() {
        return $this->belongsTo(Form_question::class);
    }
}
