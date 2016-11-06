<?php

namespace App\Http\Controllers;

use Auth;
use App\DataTables\LeaveDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;
use App\Repositories\LeaveRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\User;
use App\Models\UserLeave;
use App\Models\Constant;

class LeaveController extends AppBaseController
{
    /** @var  LeaveRepository */
    private $leaveRepository;

    public function __construct(LeaveRepository $leaveRepo)
    {
        $this->leaveRepository = $leaveRepo;
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
        $users = [''=>''] +User::pluck('name', 'id')->all();
        $statuses = [''=>''] +Constant::pluck('name', 'id')->all();
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
        $statuses = [''=>''] +Constant::pluck('name', 'id')->all();
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
    public function leaveSubmission(LeaveDataTable $leaveDataTable)
    {
        $user = Auth::user();
        $userLeave = UserLeave::where('user_id',$user->id)->first();
        return $leaveDataTable->render('leaves.submit', array('userLeave'=>$userLeave));
    }
}
