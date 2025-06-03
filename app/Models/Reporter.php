<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reporter extends Model
{

    protected $fillable = ['name', 'npm', 'telephone'];

    public function reports() {
        return $this->hasMany(Report::class);
    }

}
