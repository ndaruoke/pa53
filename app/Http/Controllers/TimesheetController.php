<?php

namespace App\Http\Controllers;

use App\DataTables\ModerationTimesheetDataTable;
use App\DataTables\TimesheetDetailDataTable;
use App\DataTables\TimesheetDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTimesheetRequest;
use App\Http\Requests\UpdateTimesheetRequest;
use App\Models\Timesheet;
use App\Models\TimesheetDetail;
use App\Models\User;
use App\Models\Project;
use App\Models\UserLeave;
use App\Repositories\TimesheetRepository;
use App\Repositories\TimesheetDetailRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use Auth;

class TimesheetController extends AppBaseController
{
    /** @var  TimesheetRepository */
    private $timesheetRepository;
    private $timesheetDetailRepository;

    public function __construct(TimesheetRepository $timesheetRepo, TimesheetDetailRepository $timesheetDetailRepo)
    {
        $this->middleware('auth');
        $this->timesheetRepository = $timesheetRepo;
        $this->timesheetDetailRepository = $timesheetDetailRepo;
    }

    /**
     * Display a listing of the Timesheet.
     *
     * @param TimesheetDataTable $timesheetDataTable
     * @return Response
     */
    public function index(TimesheetDataTable $timesheetDataTable)
    {
        return $timesheetDataTable->render('timesheets.index');
    }

    /**
     * Show the form for creating a new Timesheet.
     *
     * @return Response
     */
    public function create()
    {
        $timesheetDetails = $this->timesheetDetailRepository->all();
        $users = [''=>''] +User::pluck('name', 'id')->all();
        return view('timesheets.create',compact('timesheetDetails', users));
    }

    /**
     * Store a newly created Timesheet in storage.
     *
     * @param CreateTimesheetRequest $request
     *
     * @return Response
     */
    public function store(CreateTimesheetRequest $request)
    {
        $input = $request->all();

        $timesheet = $this->timesheetRepository->create($input);

        Flash::success('Timesheet saved successfully.');

        return redirect(route('timesheets.index'));
    }

    /**
     * Display the specified Timesheet.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $timesheet = $this->timesheetRepository->with('users')->findWithoutFail($id);

        if (empty($timesheet)) {
            Flash::error('Timesheet not found');

            return redirect(route('timesheets.index'));
        }

        return view('timesheets.show')->with('timesheet', $timesheet);
    }

    /**
     * Show the form for editing the specified Timesheet.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $timesheet = $this->timesheetRepository->findWithoutFail($id);

        if (empty($timesheet)) {
            Flash::error('Timesheet not found');

            return redirect(route('timesheets.index'));
        }
        $users = [''=>''] +User::pluck('name', 'id')->all();
        $timesheetDetails = $this->timesheetDetailRepository->whereIn('timesheet_id',$id);
        return view('timesheets.edit',compact('$timesheet','users', 'timesheetDetails'));
    }

    /**
     * Update the specified Timesheet in storage.
     *
     * @param  int              $id
     * @param UpdateTimesheetRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTimesheetRequest $request)
    {
        $timesheet = $this->timesheetRepository->findWithoutFail($id);

        if (empty($timesheet)) {
            Flash::error('Timesheet not found');

            return redirect(route('timesheets.index'));
        }

        $timesheet = $this->timesheetRepository->update($request->all(), $id);

        Flash::success('Timesheet updated successfully.');

        return redirect(route('timesheets.index'));
    }

    /**
     * Remove the specified Timesheet from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $timesheet = $this->timesheetRepository->findWithoutFail($id);

        if (empty($timesheet)) {
            Flash::error('Timesheet not found');

            return redirect(route('timesheets.index'));
        }

        $this->timesheetRepository->delete($id);

        Flash::success('Timesheet deleted successfully.');

        return redirect(route('timesheets.index'));
    }

}
