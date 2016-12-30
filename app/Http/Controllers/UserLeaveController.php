<?php

namespace App\Http\Controllers;

use App\DataTables\UserLeaveDataTable;
use App\Http\Requests\CreateUserLeaveRequest;
use App\Http\Requests\UpdateUserLeaveRequest;
use App\Models\Constant;
use App\Models\User;
use App\Repositories\UserLeaveRepository;
use Flash;
use Response;

class UserLeaveController extends AppBaseController
{
    /** @var  UserLeaveRepository */
    private $userLeaveRepository;

    public function __construct(UserLeaveRepository $userLeaveRepo)
    {
        $this->middleware('auth');
        $this->userLeaveRepository = $userLeaveRepo;
    }

    /**
     * Display a listing of the UserLeave.
     *
     * @param UserLeaveDataTable $userLeaveDataTable
     * @return Response
     */
    public function index(UserLeaveDataTable $userLeaveDataTable)
    {
        return $userLeaveDataTable->render('user_leaves.index');
    }

    /**
     * Show the form for creating a new UserLeave.
     *
     * @return Response
     */
    public function create()
    {
        $users = ['' => ''] + User::pluck('name', 'id')->all();
        $statuses = ['' => ''] + Constant::where('category', 'Status')->orderBy('name', 'asc')->pluck('name', 'id')->all();
        return view('user_leaves.create', compact('users', 'statuses'));
    }

    /**
     * Store a newly created UserLeave in storage.
     *
     * @param CreateUserLeaveRequest $request
     *
     * @return Response
     */
    public function store(CreateUserLeaveRequest $request)
    {
        $input = $request->all();

        $userLeave = $this->userLeaveRepository->create($input);

        Flash::success('User Leave saved successfully.');

        return redirect(route('userLeaves.index'));
    }

    /**
     * Display the specified UserLeave.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $userLeave = $this->userLeaveRepository->with('users')->with('statuses')->findWithoutFail($id);

        if (empty($userLeave)) {
            Flash::error('User Leave not found');

            return redirect(route('userLeaves.index'));
        }

        return view('user_leaves.show')->with('userLeave', $userLeave);
    }

    /**
     * Show the form for editing the specified UserLeave.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $userLeave = $this->userLeaveRepository->findWithoutFail($id);

        if (empty($userLeave)) {
            Flash::error('User Leave not found');

            return redirect(route('userLeaves.index'));
        }

        $users = ['' => ''] + User::pluck('name', 'id')->all();
        $statuses = ['' => ''] + Constant::where('category', 'Status')->orderBy('name', 'asc')->pluck('name', 'id')->all();
        return view('user_leaves.edit', compact('userLeave', 'users', 'statuses'));
    }

    /**
     * Update the specified UserLeave in storage.
     *
     * @param  int $id
     * @param UpdateUserLeaveRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserLeaveRequest $request)
    {
        $userLeave = $this->userLeaveRepository->findWithoutFail($id);

        if (empty($userLeave)) {
            Flash::error('User Leave not found');

            return redirect(route('userLeaves.index'));
        }

        $userLeave = $this->userLeaveRepository->update($request->all(), $id);

        Flash::success('User Leave updated successfully.');

        return redirect(route('userLeaves.index'));
    }

    /**
     * Remove the specified UserLeave from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $userLeave = $this->userLeaveRepository->findWithoutFail($id);

        if (empty($userLeave)) {
            Flash::error('User Leave not found');

            return redirect(route('userLeaves.index'));
        }

        $this->userLeaveRepository->delete($id);

        Flash::success('User Leave deleted successfully.');

        return redirect(route('userLeaves.index'));
    }
}
