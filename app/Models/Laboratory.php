<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    protected $table = 'laboratories';
    
    protected $fillable = [
        'id',
        'lab_name',
        'technician_id'
    ];
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
    public function pcs()
    {
        return $this->hasMany(Pc::class, 'lab_id');
    }
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

}
