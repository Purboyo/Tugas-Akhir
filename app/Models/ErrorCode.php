<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorCode extends Model
{
    protected $fillable = [
        'code',
        'description',
    ]; 
}
