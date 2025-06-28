<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reminder extends Model
{
    protected $fillable = [
        'title',
        'description',
        'reminder_date',
        'laboratory_id',
        'user_id',
        // 'status'
    ];

    protected $casts = [
        'reminder_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class, 'laboratory_id');
    }

    public function maintenance()
    {
        return $this->hasOne(Maintenance::class, 'reminder_id');
    }

    public function historyMaintenance()
    {
        return $this->hasOneThrough(
            HistoryMaintenance::class,
            Maintenance::class,
            'reminder_id',      // Foreign key on Maintenance table
            'maintenance_id',   // Foreign key on HistoryMaintenance table
            'id',               // Local key on Reminder table
            'id'                // Local key on Maintenance table
        );
    }

    public function getComputedStatusAttribute()
    {
        if ($this->historyMaintenance) {
            return 'completed';
        }

        $reminderDate = Carbon::parse($this->reminder_date)->startOfDay();
        $today = now()->startOfDay();

        if ($reminderDate->lt($today)) {
            return 'missed';
        }

        return 'pending';
    }
}
