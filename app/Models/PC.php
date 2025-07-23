<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PC extends Model
{
    protected $table = 'pcs';

    protected $fillable = [
        'pc_name',
        'lab_id',
        'qr_code'
    ];

    public function lab()
    {
        return $this->belongsTo(Laboratory::class, 'lab_id');
    }
}
