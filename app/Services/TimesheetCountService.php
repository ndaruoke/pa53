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
    public $timesheetApprovedAndFinanceApproved;
    public $timesheetApprovedAndFinancePaid;
    public $timesheetApprovedAndFinanceHold;
    public $timesheetApprovedAndFinanceOverBudget;

    public function __construct()
    {
        $user = Auth::user();

        $this->timesheetpending = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 0)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
        $this->timesheetapproved = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 1)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
        $this->timesheetrejected = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 2)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
        $this->timesheetpaid = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 4)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
        $this->timesheetonhold = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 5)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();
        $this->timesheetoverbudget = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 6)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->count();

        $this->timesheetApprovedAndFinanceApproved = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 1)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->whereIn('timesheet_details.id', function($q){
                $q->select('transaction_id')->from('approval_histories')
                    ->where('sequence_id', '=', '2')
                    ->where('approval_status', '=', '1');
            }) // is finance and approved
            ->count();

        $this->timesheetApprovedAndFinancePaid = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 1)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->whereIn('timesheet_details.id', function($q){
                $q->select('transaction_id')->from('approval_histories')
                    ->where('sequence_id', '=', '2')
                    ->where('approval_status', '=', '4');
            }) // is finance and paid
            ->count();

        $this->timesheetApprovedAndFinanceHold = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 1)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->whereIn('timesheet_details.id', function($q){
                $q->select('transaction_id')->from('approval_histories')
                    ->where('sequence_id', '=', '2')
                    ->where('approval_status', '=', '5');
            }) // is finance and onhold
            ->count();

        $this->timesheetApprovedAndFinanceOverBudget = DB::table('timesheet_details')
            ->join('approval_histories', 'approval_histories.transaction_id', 'timesheet_details.id')
            ->join('users', 'users.id', 'approval_histories.user_id')
            ->where('approval_histories.approval_status', '=', 1)
            ->where('transaction_type', '=', 2)
            ->where(function ($query) use ($user) {
                $query->where('approval_histories.approval_id', '=', $user->id)
                    ->orWhere('approval_histories.group_approval_id', '=', $user->role);
            })
            ->whereIn('timesheet_details.id', function($q){
                $q->select('transaction_id')->from('approval_histories')
                    ->where('sequence_id', '=', '2')
                    ->where('approval_status', '=', '6');
            }) // is finance and over budget
            ->count();
    }
}