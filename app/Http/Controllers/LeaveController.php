<?php

namespace App\Http\Controllers;

use Auth;
use App\DataTables\LeaveDataTable;
use App\DataTables\SubmitLeaveDataTable;
use App\DataTables\ModerationLeaveDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use App\Repositories\LeaveRepository;
use App\Repositories\UserLeaveRepository;
use App\Repositories\ApprovalHistoryRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\User;
use App\Models\Leave;
use App\Models\UserLeave;
use App\Models\Constant;
use App\Models\Project;
use App\Models\ProjectMember;

use Carbon\Carbon;
use App\Mail\LeaveSubmission;
use App\Mail\LeaveNotification;
use Illuminate\Support\Facades\Mail;

use Log;

class LeaveController extends AppBaseController
{
    /** @var  LeaveRepository */
    private $leaveRepository;
    /** @var  UserLeaveRepository */
    private $userLeaveRepository;
    /** @var  ApprovalHistoryRepository */
    private $approvalHistoryRepository;

    public function __construct(LeaveRepository $leaveRepo, UserLeaveRepository $userLeaveRepo, ApprovalHistoryRepository $approvalHistoryRepo)
    {
        $this->middleware('auth');
        $this->leaveRepository = $leaveRepo;
        $this->userLeaveRepository = $userLeaveRepo;
        $this->approvalHistoryRepository = $approvalHistoryRepo;
    }

    /**
     * Display a listing of the Leave.
     *
     * @param LeaveDataTable $leaveDataTable
     * @return Response
     */
    public function index(LeaveDataTable $leaveDataTable)
    {

        return $leaveDataTable->render('leaves.index');
    }

    /**
     * Show the form for creating a new Leave.
     *
     * @return Response
     */
    public function create()
    {
        $users = [''=>''] +User::whereIn('position',[1,2,3])  //Presdir,Director,VP
					->pluck('name', 'id')->all();
        $statuses = [''=>''] +Constant::where('category','Status')->orderBy('name','asc')->pluck('name', 'id')->all();
        return view('leaves.create',compact('users','statuses'));
    }

    /**
     * Store a newly created Leave in storage.
     *
     * @param CreateLeaveRequest $request
     *
     * @return Response
     */
    public function store(CreateLeaveRequest $request)
    {
        $this->validate($request, [
            'end_date' => 'required|date|after:start_date',
        ]);

        $input = $request->all();

        $leave = $this->leaveRepository->create($input);

        Flash::success('Leave saved successfully.');

        return redirect(route('leaves.index'));
    }

    /**
     * Display the specified Leave.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $leave = $this->leaveRepository->with('users')->with('approvals')->with('statuses')->findWithoutFail($id);

        if (empty($leave)) {
            Flash::error('Leave not found');

            return redirect(route('leaves.index'));
        }

        return view('leaves.show')->with('leave', $leave);

    }

    /**
     * Show the form for editing the specified Leave.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $leave = $this->leaveRepository->findWithoutFail($id);

        if (empty($leave)) {
            Flash::error('Leave not found');

            return redirect(route('leaves.index'));
        }
        $users = [''=>''] +User::pluck('name', 'id')->all();
        $statuses = [''=>''] +Constant::where('category','Status')->orderBy('name','asc')->pluck('name', 'id')->all();
        return view('leaves.edit',compact('leave','users','statuses'));
        //return view('leaves.edit')->with('leave', $leave);
    }

    /**
     * Update the specified Leave in storage.
     *
     * @param  int              $id
     * @param UpdateLeaveRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLeaveRequest $request)
    {
        $this->validate($request, [
            'end_date' => 'required|date|after:start_date',
        ]);

        $leave = $this->leaveRepository->findWithoutFail($id);

        if (empty($leave)) {
            Flash::error('Leave not found');

            return redirect(route('leaves.index'));
        }

        $leave = $this->leaveRepository->update($request->all(), $id);

        Flash::success('Leave updated successfully.');

        return redirect(route('leaves.index'));
    }

    /**
     * Remove the specified Leave from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $leave = $this->leaveRepository->findWithoutFail($id);

        if (empty($leave)) {
            Flash::error('Leave not found');

            return redirect(route('leaves.index'));
        }

        $this->leaveRepository->delete($id);

        Flash::success('Leave deleted successfully.');

        return redirect(route('leaves.index'));
    }


    /**
     * Display a listing of the Leave.
     *
     * @param LeaveDataTable $leaveDataTable
     * @return Response
     */
    public function submission(SubmitLeaveDataTable $submitLeaveDataTable)
    {
        $user = Auth::user();
        $userLeave = UserLeave::where('user_id',$user->id)->first();
        return $submitLeaveDataTable->render('leaves.submit', array('userLeave'=>$userLeave));
    }

    /**
     * Show the form for creating a new Leave.
     *
     * @return Response
     */
    public function submissionCreate()
    {
        $users = [''=>''] +User::pluck('name', 'id')->all();
        $statuses = [''=>''] +Constant::where('category','Status')->orderBy('name','asc')->pluck('name', 'value')->all();
        $types = [''=>''] +Constant::where('category','Cuti')->orderBy('name','asc')->pluck('name', 'value')->all();
		$projects = $this->getProject();

		if($projects!=null){
			$projects = [''=>''] +$projects->pluck('project_name', 'id')->all();
			return view('leaves.submit_create',compact('users','statuses','types','projects'));
		}
		else 
		{
			return view('leaves.submit_create',compact('users','statuses','types'));
		}
        
    }

    /**
     * Store a newly created Leave in storage.
     *
     * @param CreateLeaveRequest $request
     *
     * @return Response
     */
    public function submissionStore(CreateLeaveRequest $request)
    {
        $this->validate($request, [
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date-1',
        ]);

        $user = Auth::user();
        $approver = $this->getApprover($request);
        
        if (empty($approver)) {
            Flash::error('Approver not found');

            return redirect(route('leaves.submission'));
        }

        $request->merge(array('status' => 1));
        $request->merge(array('user_id' => $user->id));
         
        $request->merge(array('approval_id' => $approver->id));

        $input = $request->all();

        $leave = $this->leaveRepository->create($input);

        $approvalHistory = $this->createFromLeave($leave);
        $approvalHistory = $this->approvalHistoryRepository->create($approvalHistory);

        $startDate = new Carbon($request->start_date);
        $endDate = new Carbon($request->end_date);
        $dayCount = $endDate->diff($startDate)->days;

        $userLeave = UserLeave::where('user_id','=',$user->id)->get()->first();

        $userLeave->leave_used += $dayCount+1;
        $input = $userLeave->toArray();

        $userLeave = $this->userLeaveRepository->update($input, $userLeave->id);
		
        $cc = $this->getCC($request);

		//send email
		Mail::to($approver->email)
                ->cc($cc)
                ->queue(new LeaveSubmission($request->all(), $approver));

        Flash::success('Leave saved successfully.');

        return redirect(route('leaves.submission'));
    }
	
    //TODO make flexible in table
	private function getApprover(CreateLeaveRequest $request)
	{
		$user = Auth::user();
		
		$consultantPosition = [6,7,8,9,16];
        $managingConsultantPosition = [4,5];
        $bod = [1,2,3];

		if($request->project != null) // consultant to PM
		{
			$project = Project::where('id', $request->project)->get();
            $approver = User::where('id',$project->first()->pm_user_id)
                ->get();
			return $approver->first();
		}
        if(in_array($user->position, $consultantPosition)) // PM to Managing Consultant
        {
            $approver = User::whereIn('position',$managingConsultantPosition)
                ->where('department',$user->department)->get();
            return $approver->first();
        }

		if(in_array($user->position, $managingConsultantPosition)) // Managing Consultant to Vice President
        {
            $approver = User::where('position',3)
                ->where('department',$user->department)->get();
            return $approver->first();
        }

        if(in_array($user->position, $bod)) // VP, Director auto approve admin
        {
            return $user;
        }

		else
		{
			return $user->id;
		}
	}

    

	public function getProject()
	{
		$user = Auth::user();
				
		$projectMember = ProjectMember::where('user_id', $user->id)->where('deleted_at', null)->get();

		if (count($projectMember)) {
			$project = Project::whereIn('id', $projectMember->pluck('id')->where('deleted_at', null))->get();
			return $project;
		}
		else 
		{
			return null;
		}
		
	}

    /**
     * Update the specified Leave in storage.
     *
     * @param  int              $id
     * @param UpdateLeaveRequest $request
     *
     * @return Response
     */
    public function submissionUpdate($id, UpdateLeaveRequest $request)
    {
        $this->validate($request, [
            'end_date' => 'required|date|after:start_date',
        ]);

        $leave = $this->leaveRepository->findWithoutFail($id);

        if (empty($leave)) {
            Flash::error('Leave not found');

            return redirect(route('leaves.index'));
        }

        $leave = $this->leaveRepository->update($request->all(), $id);

        Flash::success('Leave updated successfully.');

        return redirect(route('cuti.submission'));
    }

    /**
     * Display the specified Leave.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function submissionShow($id)
    {
        $leave = $this->leaveRepository->with('users')->with('approvals')->with('statuses')->findWithoutFail($id);

        if (empty($leave)) {
            Flash::error('Leave not found');

            return redirect(route('leaves.submission'));
        }

        return view('leaves.submit_show')->with('leave', $leave);

    }

        /**
     * Display a listing of the Leave.
     *
     * @param LeaveDataTable $leaveDataTable
     * @return Response
     */
    public function moderation(ModerationLeaveDataTable $moderationLeaveDataTable)
    {
        $user = Auth::user();
        $userLeave = UserLeave::where('user_id',$user->id)->first();
        return $moderationLeaveDataTable->render('leaves.moderation', array('userLeave'=>$userLeave));
    }

        /**
     * Display the specified Leave.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function moderationShow($id)
    {
        $leave = $this->leaveRepository->with('users')->with('approvals')->with('statuses')->findWithoutFail($id);

        if (empty($leave)) {
            Flash::error('Leave not found');

            return redirect(route('leaves.moderation'));
        }

        return view('leaves.moderation_show')->with('leave', $leave);

    }

            /**
     * Display the specified Leave.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function moderationEdit($id)
    {
        $leave = $this->leaveRepository->with('users')->with('approvals')->with('statuses')->findWithoutFail($id);

        if (empty($leave)) {
            Flash::error('Leave not found');

            return redirect(route('leaves.moderation'));
        }

        if($leave->approval_status == 1 || $leave->approval_status == 2) {//approved or rejected
            Flash::error('Data '.$leave->note.' has been approved or rejected');

            return redirect(route('leaves.moderation'));
        }

        return view('leaves.moderation_edit')->with('leave', $leave);

    }

    /**
     * Approve Leave in storage.
     *
     * @param CreateLeaveRequest $request
     *
     * @return Response
     */
    public function moderationApprove($id)
    {
        //approve
        $leave = Leave::approve($id);

        if ($leave == false) {
            Flash::error('Approve Leave Fail');

            return redirect(route('leaves.moderation'));
        }

        $leave = $this->leaveRepository->findWithoutFail($id);
        $cc = $this->getHRD(); 
        $approver = User::where('id',$leave->approval_id)->get()->first();
        $user = User::where('id',$leave->user_id)->get()->first();

        //send email
		Mail::to($user->email)
                ->cc($cc)
                ->queue(new LeaveNotification($leave, $approver, $user, 'approved'));

        Flash::success('Approve successfully.');

        return redirect(route('leaves.moderation'));
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
        $leave = $this->leaveRepository->findWithoutFail($id);
        if (empty($leave)) {
            Flash::error('Leave not found');

            return redirect(route('leaves.moderation'));
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

    private function getHRD()
	{
        $hrd = [17];

        $cc = User::whereIn('position',$hrd)->get();
        return $cc->pluck('email')->all(); 
    }

    private function createFromLeave(Leave $leave)
    {
        return array(
                    'date' => Carbon::now(),
                    'note' => $leave->note,
                    'sequence_id' => 0,
                    'transaction_id' => $leave->id,
                    'transaction_type' => 0, //leave
                    'approval_status' => $leave->approval_status,
                    'user_id' => $leave->user_id,
                    'approval_id' => $leave->approval_id
                 );
    }
}
