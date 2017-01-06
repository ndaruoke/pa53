<?php

namespace App\Services;

use Auth;
use DB;

class TimesheetCountService
{
    public $timesheetapproved;
    public $timesheetpending;
    public $timesheetrejected;
    public $timesheetpaid;
    public $timesheetonhold;
    public $timesheetoverbudget;

    public function __construct()
    {
        $user = Auth::user();

        $this->timesheetpending = $this->timesheet = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 0)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
        $this->timesheetapproved = $this->timesheet = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 1)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
        $this->timesheetrejected = $this->timesheet = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 2)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
        $this->timesheetpaid = $this->timesheet = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 4)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
        $this->timesheetonhold = $this->timesheet = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 5)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
        $this->timesheetoverbudget = $this->timesheet = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 6)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
    }
}