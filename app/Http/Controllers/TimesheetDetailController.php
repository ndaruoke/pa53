<?php

namespace App\Http\Controllers;

use App\DataTables\TimesheetDetailDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTimesheetDetailRequest;
use App\Http\Requests\UpdateTimesheetDetailRequest;
use App\Repositories\TimesheetDetailRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class TimesheetDetailController extends AppBaseController
{
    /** @var  TimesheetDetailRepository */
    private $timesheetDetailRepository;

    public function __construct(TimesheetDetailRepository $timesheetDetailRepo)
    {
        $this->middleware('auth');
        $this->timesheetDetailRepository = $timesheetDetailRepo;
    }

    /**
     * Display a listing of the TimesheetDetail.
     *
     * @param TimesheetDetailDataTable $timesheetDetailDataTable
     * @return Response
     */
    public function index(TimesheetDetailDataTable $timesheetDetailDataTable)
    {
        return $timesheetDetailDataTable->render('timesheet_details.index');
    }

    /**
     * Show the form for creating a new TimesheetDetail.
     *
     * @return Response
     */
    public function create()
    {
        return view('timesheet_details.create');
    }

    /**
     * Store a newly created TimesheetDetail in storage.
     *
     * @param CreateTimesheetDetailRequest $request
     *
     * @return Response
     */
    public function store(CreateTimesheetDetailRequest $request)
    {
        $input = $request->all();

        $timesheetDetail = $this->timesheetDetailRepository->create($input);

        Flash::success('Timesheet Detail saved successfully.');

        return redirect(route('timesheetDetails.index'));
    }

    /**
     * Display the specified TimesheetDetail.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $timesheetDetail = $this->timesheetDetailRepository->findWithoutFail($id);

        if (empty($timesheetDetail)) {
            Flash::error('Timesheet Detail not found');

            return redirect(route('timesheetDetails.index'));
        }

        return view('timesheet_details.show')->with('timesheetDetail', $timesheetDetail);
    }

    /**
     * Show the form for editing the specified TimesheetDetail.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $timesheetDetail = $this->timesheetDetailRepository->findWithoutFail($id);

        if (empty($timesheetDetail)) {
            Flash::error('Timesheet Detail not found');

            return redirect(route('timesheetDetails.index'));
        }

        return view('timesheet_details.edit')->with('timesheetDetail', $timesheetDetail);
    }

    /**
     * Update the specified TimesheetDetail in storage.
     *
     * @param  int              $id
     * @param UpdateTimesheetDetailRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTimesheetDetailRequest $request)
    {
        $timesheetDetail = $this->timesheetDetailRepository->findWithoutFail($id);

        if (empty($timesheetDetail)) {
            Flash::error('Timesheet Detail not found');

            return redirect(route('timesheetDetails.index'));
        }

        $timesheetDetail = $this->timesheetDetailRepository->update($request->all(), $id);

        Flash::success('Timesheet Detail updated successfully.');

        return redirect(route('timesheetDetails.index'));
    }

    /**
     * Remove the specified TimesheetDetail from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $timesheetDetail = $this->timesheetDetailRepository->findWithoutFail($id);

        if (empty($timesheetDetail)) {
            Flash::error('Timesheet Detail not found');

            return redirect(route('timesheetDetails.index'));
        }

        $this->timesheetDetailRepository->delete($id);

        Flash::success('Timesheet Detail deleted successfully.');

        return redirect(route('timesheetDetails.index'));
    }
}
