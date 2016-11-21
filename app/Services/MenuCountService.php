<?php

namespace App\Services;

use Auth;
use App\Models\Leave;

class MenuCountService
{
    public $leave;

    public function __construct()
    {
        $user = Auth::user();
        $this->leave = Leave::pending()->where('approval_id',$user->id)->count();
    }
}