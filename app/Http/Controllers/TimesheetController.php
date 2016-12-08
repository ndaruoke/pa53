<?php

namespace App\Http\Controllers;

use App\DataTables\TimesheetDetailDataTable;
use App\DataTables\TimesheetDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTimesheetRequest;
use App\Http\Requests\UpdateTimesheetRequest;
use App\Models\TimesheetDetail;
use App\Models\User;
use App\Repositories\TimesheetRepository;
use App\Repositories\TimesheetDetailRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

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

    /**
     * Approve Timesheet in storage.
     *
     * @param CreateTimesheetRequest $request
     *
     * @return Response
     */
    public function moderationApprove($id)
    {
        //approve
        $tiemsheet = Timesheet::approve($id);

        if ($leave == false) {
            Flash::error('Approve Timesheet Fail');

            return redirect(route('timesheet.moderation'));
        }

        $timesheet = $this->timesheetRepository->findWithoutFail($id);
        $cc = $this->getHRD(); 
        $approver = User::where('id',$timesheet->approval_id)->get()->first();
        $user = User::where('id',$timesheet->user_id)->get()->first();

        //send email
		Mail::to($user->email)
                ->cc($cc)
                ->queue(new TimesheetNotification($timesheet, $approver, $user, 'approved'));

        Flash::success('Approve successfully.');

        return redirect(route('timesheet.moderation'));
    }



    /**
     * Reject Leave in storage.
     *
     * @param CreateLeaveRequest $request
     *
     * @return Response
     */
    public function moderationReject($id)
    {
        $timesheet = $this->timesheetRepository->findWithoutFail($id);
        if (empty($timesheet)) {
            Flash::error('Timesheet not found');

            return redirect(route('timesheet.moderation'));
        }
        $userLeave = $this->userLeaveRepository->findByField('user_id',$leave->user_id)->first();
        if (empty($userLeave)) {
            Flash::error('User Leave not found');

            return redirect(route('leaves.moderation'));
        }

        $endDate = new Carbon($leave->end_date);
        $startDate = new Carbon($leave->start_date);
        $dayCount = $endDate->diff($startDate)->days;
        $userLeave->leave_used = $userLeave->leave_used-$dayCount;
        
        $userLeave->save();

        $leave = Leave::reject($id);

        if ($leave == false) {
            Flash::error('Reject Leave Fail');

            return redirect(route('leaves.moderation'));
        }

        $leave = $this->leaveRepository->findWithoutFail($id);
        $approver = User::where('id',$leave->approval_id)->get()->first();
        $user = User::where('id',$leave->user_id)->get()->first();
        //send email
		Mail::to($user->email)
                ->queue(new LeaveNotification($leave, $approver, $user, 'rejected'));

        Flash::success('Reject successfully.');

        return redirect(route('leaves.moderation'));
    }

    private function getCC(CreateLeaveRequest $request)
	{
		$user = Auth::user();
		
		$consultantPosition = [6,7,8,9,16];
        $managingConsultantPosition = [4,5];
        $bod = [1,2,3];;

		if($request->project != null) // Consultant to MC + HRD
		{
			$cc = User::whereIn('position',$managingConsultantPosition)
                ->where('department',$user->department)->get();
			return $cc->pluck('email')->all()->first();
		}
        if(in_array($user->position, $consultantPosition)) // PM to VP+HRD
        {
            $cc = User::where('position',3)
                ->where('department',$user->department)->get();
            return $cc->pluck('email')->all()->first();
        }

		if(in_array($user->position, $managingConsultantPosition)) // Managing Consultant to Vice President
        {
            return $this->getHRD();
        }

        if(in_array($user->position, $bod)) // VP, Director auto approve admin
        {
            return $this->getHRD();
        }

		else
		{
			return $this->getHRD();
        }
    }
}
