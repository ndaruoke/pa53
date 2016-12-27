<?php

namespace App\Services;

use Auth;
use App\Models\Leave;
use App\Models\Timesheet;
use DB;

class MenuCountService
{
    public $leave;
    public $timesheet;

    public function __construct()
    {
        $user = Auth::user();
        $this->leave = Leave::pending()->where('approval_id',$user->id)->count();
        $this->timesheet = DB::table('timesheet_details')
            ->select('approval_histories.user_id')
            ->join('approval_histories','approval_histories.transaction_id','timesheet_details.id')
            ->join('users','users.id','approval_histories.user_id')
            ->where('approval_histories.approval_status','=',0)
            ->where('transaction_type','=', 2)
            ->where(function ($query) use($user){
                    $query->where('approval_histories.approval_id','=',$user->id)
                            ->orWhere('approval_histories.group_approval_id','=', $user->role);
                })
            ->groupBy('user_id')
            ->count();
    }
}