<?php

namespace App\Http\Controllers;

use DB;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Input;
use Response;
use Auth;
use App\Models\Project;
use Carbon\Carbon;
use Request as RequestFacade;
use Yajra\Datatables\Facades\Datatables;
class ReportMappingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $request = RequestFacade::all();
        $p_id = '';
        if(isset($request['project'])){
            $p_id = $request['project'];
        }
        $notes = DB::select(DB::raw("select users.nik, users.name as full_name, departments.name as position_based_on_structure, departments.note as divisi, m.name as report_to from users,project_members, departments, users m , projects where users.id = project_members.user_id and users.department = departments.id and m.id = projects.pm_user_id and projects.id = project_members.project_id and project_members.project_id = ".$p_id));
        $columns = ['nik','full_name', 'position_based_on_structure', 'divisi','report_to'];
        if (RequestFacade::ajax()) {
            return Datatables::of(collect($notes))->make(true);;
        }
        $html = Datatables::getHtmlBuilder()->columns($columns);    
        $projects = array(0 => 'all') + Project::pluck('project_name', 'id')->all();
        return view('reports.mapping',compact('projects','request','html'));
    }
}
