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
    public function timesheet(TimesheetReportDataTable $TimesheetReportDataTable)
    {
        $user = Auth::User();
        if (empty($_REQUEST['reportType'])) {
            $reportType = 1;
        } else {
            $reportType = $_REQUEST['reportType'];
        }
        return $TimesheetReportDataTable->render('reports.timesheet', array('reportType' => $reportType, 'user' => $user));
    }



}