<?php

namespace App\Services;

use Auth;
use App\Models\Leave;
use App\Models\Timesheet;

class TimesheetCountService
{
    public $timesheetapproved;
    public $timesheetpending;
    public $timesheetrejected;

    public function __construct()
    {
        $user = Auth::user();

        $this->timesheetpending = Timesheet::pending()->where('approval_id',$user->id)->count();
        $this->timesheetapproved = Timesheet::where('approval_status',1)->where('approval_id',$user->id)->count();
        $this->timesheetrejected = Timesheet::rejected()->where('approval_id',$user->id)->count();
    }
}