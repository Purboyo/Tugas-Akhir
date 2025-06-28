<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'reminder_id', 
        'note'];
        // 'laboratory_id', 
        // 'user_id', 

//     public function laboratory()
// {
//     return $this->belongsTo(Laboratory::class, 'laboratory_id');
// }

// public function user()
// {
//     return $this->belongsTo(User::class, 'user_id');
// }
public function reminder()
{
    return $this->belongsTo(Reminder::class);
}
}
