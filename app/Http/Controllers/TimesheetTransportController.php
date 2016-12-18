<?php

namespace App\Http\Controllers;

use App\DataTables\TimesheetTransportDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTimesheetTransportRequest;
use App\Http\Requests\UpdateTimesheetTransportRequest;
use App\Repositories\TimesheetTransportRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class TimesheetTransportController extends AppBaseController
{
    /** @var  TimesheetTransportRepository */
    private $timesheetTransportRepository;

    public function __construct(TimesheetTransportRepository $timesheetTransportRepo)
    {
        $this->timesheetTransportRepository = $timesheetTransportRepo;
    }

    /**
     * Display a listing of the TimesheetTransport.
     *
     * @param TimesheetTransportDataTable $timesheetTransportDataTable
     * @return Response
     */
    public function index(TimesheetTransportDataTable $timesheetTransportDataTable)
    {
        return $timesheetTransportDataTable->render('timesheet_transports.index');
    }

    /**
     * Show the form for creating a new TimesheetTransport.
     *
     * @return Response
     */
    public function create()
    {
        return view('timesheet_transports.create');
    }

    /**
     * Store a newly created TimesheetTransport in storage.
     *
     * @param CreateTimesheetTransportRequest $request
     *
     * @return Response
     */
    public function store(CreateTimesheetTransportRequest $request)
    {
        $input = $request->all();

        $timesheetTransport = $this->timesheetTransportRepository->create($input);

        Flash::success('Timesheet Transport saved successfully.');

        return redirect(route('timesheetTransports.index'));
    }

    /**
     * Display the specified TimesheetTransport.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $timesheetTransport = $this->timesheetTransportRepository->findWithoutFail($id);

        if (empty($timesheetTransport)) {
            Flash::error('Timesheet Transport not found');

            return redirect(route('timesheetTransports.index'));
        }

        return view('timesheet_transports.show')->with('timesheetTransport', $timesheetTransport);
    }

    /**
     * Show the form for editing the specified TimesheetTransport.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $timesheetTransport = $this->timesheetTransportRepository->findWithoutFail($id);

        if (empty($timesheetTransport)) {
            Flash::error('Timesheet Transport not found');

            return redirect(route('timesheetTransports.index'));
        }

        return view('timesheet_transports.edit')->with('timesheetTransport', $timesheetTransport);
    }

    /**
     * Update the specified TimesheetTransport in storage.
     *
     * @param  int              $id
     * @param UpdateTimesheetTransportRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTimesheetTransportRequest $request)
    {
        $timesheetTransport = $this->timesheetTransportRepository->findWithoutFail($id);

        if (empty($timesheetTransport)) {
            Flash::error('Timesheet Transport not found');

            return redirect(route('timesheetTransports.index'));
        }

        $timesheetTransport = $this->timesheetTransportRepository->update($request->all(), $id);

        Flash::success('Timesheet Transport updated successfully.');

        return redirect(route('timesheetTransports.index'));
    }

    /**
     * Remove the specified TimesheetTransport from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $timesheetTransport = $this->timesheetTransportRepository->findWithoutFail($id);

        if (empty($timesheetTransport)) {
            Flash::error('Timesheet Transport not found');

            return redirect(route('timesheetTransports.index'));
        }

        $this->timesheetTransportRepository->delete($id);

        Flash::success('Timesheet Transport deleted successfully.');

        return redirect(route('timesheetTransports.index'));
    }
}
