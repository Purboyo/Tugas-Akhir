<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class report extends Model
{
    protected $fillable = [
        'reporter_id',
        'form_id',
        'pc_id',
        'technician_id',
        'status',
        'description',
        'report_to_head_at',
        'remark_to_head',
    ];

    public function reporter() {
        return $this->belongsTo(Reporter::class);
    }

    public function form() {
        return $this->belongsTo(Form::class);
    }

    public function pc()
    {
        return $this->belongsTo(PC::class, 'pc_id');
    }

    public function answers() {
        return $this->hasMany(Report_answer::class);
    }
    public function lab()
    {
        return $this->belongsTo(Laboratory::class, 'lab_id');
    }
}
