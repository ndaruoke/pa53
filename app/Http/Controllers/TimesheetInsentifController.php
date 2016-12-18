<?php

namespace App\Http\Controllers;

use App\DataTables\TimesheetInsentifDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTimesheetInsentifRequest;
use App\Http\Requests\UpdateTimesheetInsentifRequest;
use App\Repositories\TimesheetInsentifRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class TimesheetInsentifController extends AppBaseController
{
    /** @var  TimesheetInsentifRepository */
    private $timesheetInsentifRepository;

    public function __construct(TimesheetInsentifRepository $timesheetInsentifRepo)
    {
        $this->timesheetInsentifRepository = $timesheetInsentifRepo;
    }

    /**
     * Display a listing of the TimesheetInsentif.
     *
     * @param TimesheetInsentifDataTable $timesheetInsentifDataTable
     * @return Response
     */
    public function index(TimesheetInsentifDataTable $timesheetInsentifDataTable)
    {
        return $timesheetInsentifDataTable->render('timesheet_insentifs.index');
    }

    /**
     * Show the form for creating a new TimesheetInsentif.
     *
     * @return Response
     */
    public function create()
    {
        return view('timesheet_insentifs.create');
    }

    /**
     * Store a newly created TimesheetInsentif in storage.
     *
     * @param CreateTimesheetInsentifRequest $request
     *
     * @return Response
     */
    public function store(CreateTimesheetInsentifRequest $request)
    {
        $input = $request->all();

        $timesheetInsentif = $this->timesheetInsentifRepository->create($input);

        Flash::success('Timesheet Insentif saved successfully.');

        return redirect(route('timesheetInsentifs.index'));
    }

    /**
     * Display the specified TimesheetInsentif.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $timesheetInsentif = $this->timesheetInsentifRepository->findWithoutFail($id);

        if (empty($timesheetInsentif)) {
            Flash::error('Timesheet Insentif not found');

            return redirect(route('timesheetInsentifs.index'));
        }

        return view('timesheet_insentifs.show')->with('timesheetInsentif', $timesheetInsentif);
    }

    /**
     * Show the form for editing the specified TimesheetInsentif.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $timesheetInsentif = $this->timesheetInsentifRepository->findWithoutFail($id);

        if (empty($timesheetInsentif)) {
            Flash::error('Timesheet Insentif not found');

            return redirect(route('timesheetInsentifs.index'));
        }

        return view('timesheet_insentifs.edit')->with('timesheetInsentif', $timesheetInsentif);
    }

    /**
     * Update the specified TimesheetInsentif in storage.
     *
     * @param  int              $id
     * @param UpdateTimesheetInsentifRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTimesheetInsentifRequest $request)
    {
        $timesheetInsentif = $this->timesheetInsentifRepository->findWithoutFail($id);

        if (empty($timesheetInsentif)) {
            Flash::error('Timesheet Insentif not found');

            return redirect(route('timesheetInsentifs.index'));
        }

        $timesheetInsentif = $this->timesheetInsentifRepository->update($request->all(), $id);

        Flash::success('Timesheet Insentif updated successfully.');

        return redirect(route('timesheetInsentifs.index'));
    }

    /**
     * Remove the specified TimesheetInsentif from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $timesheetInsentif = $this->timesheetInsentifRepository->findWithoutFail($id);

        if (empty($timesheetInsentif)) {
            Flash::error('Timesheet Insentif not found');

            return redirect(route('timesheetInsentifs.index'));
        }

        $this->timesheetInsentifRepository->delete($id);

        Flash::success('Timesheet Insentif deleted successfully.');

        return redirect(route('timesheetInsentifs.index'));
    }
}
