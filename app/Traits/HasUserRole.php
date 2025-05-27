<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasUserRole
{
    protected $role;

    public function setUserRole()
    {
        $this->role = Auth::check() ? Auth::user()->role : null;
    }
}
