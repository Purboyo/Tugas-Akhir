<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabReport extends Model
{
    protected $fillable = [
        'pc_id',
        'technician_id',
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
