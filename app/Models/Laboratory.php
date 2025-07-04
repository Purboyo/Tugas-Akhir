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

    public function forms()
    {
        return $this->belongsToMany(Form::class, 'form_laboratory');
    }

    public function reports()
    {
        return $this->hasManyThrough(
            \App\Models\Report::class,  // target model
            \App\Models\PC::class,      // intermediate model
            'lab_id',                   // Foreign key di table PCs
            'pc_id',                    // Foreign key di table reports
            'id',                       // Local key di laboratories
            'id'                        // Local key di PCs
        );
    }

}
