<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Models\Project;
use Response;


class Add_Timesheet extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }

     public function index()
    {
        $project = Project::pluck('project_name', 'id')->all();
       return view('timesheets.add_timesheet',compact('project'));
    }

}
