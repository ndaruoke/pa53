<?php

namespace App\Http\Controllers;

use App\DataTables\TimesheetReportDataTable;
use App\Http\Requests\CreateTimesheetRequest;
use App\Http\Requests\UpdateTimesheetRequest;
use App\Repositories\TimesheetRepository;
use DB;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Input;
use Response;
use Auth;
use App\Models\Project;
use Carbon\Carbon;

class ReportController extends AppBaseController
{
    /** @var  TimesheetRepository */
    private $TimesheetRepository;

    public function __construct(TimesheetRepository $TimesheetRepo)
    {
        $this->middleware('auth');
        $this->TimesheetRepository = $TimesheetRepo;
    }

    /**
     * Display a listing of the Timesheet.
     *
     * @param TimesheetReportDataTable $TimesheetReportDataTable
     * @return Response
     */
    public function timesheet(TimesheetReportDataTable $timesheetReportDataTable)
    {

        $user = Auth::User();
        $now = Carbon::now();
        if (empty($_REQUEST['reportType'])) {
            $reportType = 1;
        } else {
            $reportType = $_REQUEST['reportType'];
        }
        if (empty($_REQUEST['project']) || $_REQUEST['project'] == 0) {
            $allProject = 1;
            $project = 0;
        } else {
            $allProject = 0;
            $project = $_REQUEST['project'];
        }
        if (!empty($_REQUEST['month'])) {
            $month = $_REQUEST['month'];
        } else {
            $month = $now->month ;
        }
        if (!empty($_REQUEST['year'])) {
            $year = $_REQUEST['year'];
        } else {
            $year = $now->year ;
        }

        //$projects = array_merge(array(0 => 'all'),Project::pluck('project_name', 'id')->all());
        $projects = array(0 => 'all') + Project::pluck('project_name', 'id')->all();

        return $timesheetReportDataTable->render('reports.timesheet',
            array(
                'reportType' => $reportType,
                'allProject' => $allProject,
                'project' => $project,
                'month' => $month,
                'year' => $year,
                'user' => $user,
                'projects' => $projects
            ));
    }



}