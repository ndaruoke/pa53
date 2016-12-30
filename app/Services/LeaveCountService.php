<?php

namespace App\Services;

use App\Models\Leave;
use Auth;

class LeaveCountService
{
    public $leaveapproved;
    public $leavepending;
    public $leaverejected;

    public function __construct()
    {
        $user = Auth::user();

        $this->leavepending = Leave::pending()->where('approval_id', $user->id)->count();
        $this->leaveapproved = Leave::where('approval_status', 1)->where('approval_id', $user->id)->count();
        $this->leaverejected = Leave::rejected()->where('approval_id', $user->id)->count();
    }
}