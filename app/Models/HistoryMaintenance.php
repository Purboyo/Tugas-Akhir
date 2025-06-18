<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryMaintenance extends Model
{
    protected $fillable = ['maintenance_id', 'pc_id', 'status'];

    public function pc()
{
    return $this->belongsTo(Pc::class, 'pc_id');
}

public function maintenance()
{
    return $this->belongsTo(Maintenance::class);
}

}
