<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryReportPC extends Model
{
    protected $table = 'history_reports_pc';
    protected $fillable = [
        'pc_id',
        'technician_id',
        'id_report',
        'description',
    ];
public function pc()
{
    return $this->belongsTo(PC::class);
}

public function technician()
{
    return $this->belongsTo(User::class, 'technician_id');
}

}
