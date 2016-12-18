<?php

namespace App\Services;

use Auth;
use App\Models\Leave;
use App\Models\Timesheet;

class MenuCountService
{
    public $leave;
    public $timesheet;

    public function __construct()
    {
        $user = Auth::user();
        $this->leave = Leave::pending()->where('approval_id',$user->id)->count();
        $this->timesheet = Timesheet::pending()->where('approval_id',$user->id)->count();
    }
}