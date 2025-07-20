<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'reminder_id', 
        'note'];

public function reminder()
{
    return $this->belongsTo(Reminder::class);
}
}
