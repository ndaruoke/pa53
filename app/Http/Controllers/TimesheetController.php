<?php

namespace App\Http\Controllers;

use App\DataTables\TimesheetDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTimesheetRequest;
use App\Http\Requests\UpdateTimesheetRequest;
use App\Repositories\TimesheetRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class TimesheetController extends AppBaseController
{
    /** @var  TimesheetRepository */
    private $timesheetRepository;

    public function __construct(TimesheetRepository $timesheetRepo)
    {
        $this->timesheetRepository = $timesheetRepo;
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
        return view('timesheets.create');
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
        $timesheet = $this->timesheetRepository->findWithoutFail($id);

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

        return view('timesheets.edit')->with('timesheet', $timesheet);
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
