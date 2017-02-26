<?php

namespace App\Services;

use App\Models\Project;
use Auth;
use DB;

class IsProjectManagerService
{
    public $result;

    public function __construct()
    {
        $user = Auth::user();
        $this->result = Project::where('pm_user_id', $user->id)->count();
    }
}