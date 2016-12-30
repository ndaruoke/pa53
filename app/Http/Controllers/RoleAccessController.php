<?php

namespace App\Http\Controllers;

use App\DataTables\RoleAccessDataTable;
use App\Http\Requests\CreateRoleAccessRequest;
use App\Http\Requests\UpdateRoleAccessRequest;
use App\Models\Constant;
use App\Repositories\RoleAccessRepository;
use Flash;
use Response;

class RoleAccessController extends AppBaseController
{
    /** @var  RoleAccessRepository */
    private $roleAccessRepository;

    public function __construct(RoleAccessRepository $roleAccessRepo)
    {
        $this->middleware('auth');
        $this->roleAccessRepository = $roleAccessRepo;
    }

    /**
     * Display a listing of the RoleAccess.
     *
     * @param RoleAccessDataTable $roleAccessDataTable
     * @return Response
     */
    public function index(RoleAccessDataTable $roleAccessDataTable)
    {
        return $roleAccessDataTable->render('role_accesses.index');
    }

    /**
     * Show the form for creating a new RoleAccess.
     *
     * @return Response
     */
    public function create()
    {
        $statuses = ['' => ''] + Constant::where('category', 'Status')->orderBy('name', 'asc')->pluck('name', 'id')->all();
        return view('role_accesses.create', compact('statuses'));
    }

    /**
     * Store a newly created RoleAccess in storage.
     *
     * @param CreateRoleAccessRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleAccessRequest $request)
    {
        $input = $request->all();

        $roleAccess = $this->roleAccessRepository->create($input);

        Flash::success('Role Access saved successfully.');

        return redirect(route('roleAccesses.index'));
    }

    /**
     * Display the specified RoleAccess.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $roleAccess = $this->roleAccessRepository->with('statuses')->findWithoutFail($id);

        if (empty($roleAccess)) {
            Flash::error('Role Access not found');

            return redirect(route('roleAccesses.index'));
        }

        return view('role_accesses.show')->with('roleAccess', $roleAccess);
    }

    /**
     * Show the form for editing the specified RoleAccess.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $roleAccess = $this->roleAccessRepository->findWithoutFail($id);

        if (empty($roleAccess)) {
            Flash::error('Role Access not found');

            return redirect(route('roleAccesses.index'));
        }

        $statuses = ['' => ''] + Constant::where('category', 'Status')->orderBy('name', 'asc')->pluck('name', 'id')->all();
        return view('role_accesses.edit', compact('roleAccess', 'statuses'));
    }

    /**
     * Update the specified RoleAccess in storage.
     *
     * @param  int $id
     * @param UpdateRoleAccessRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoleAccessRequest $request)
    {
        $roleAccess = $this->roleAccessRepository->findWithoutFail($id);

        if (empty($roleAccess)) {
            Flash::error('Role Access not found');

            return redirect(route('roleAccesses.index'));
        }

        $roleAccess = $this->roleAccessRepository->update($request->all(), $id);

        Flash::success('Role Access updated successfully.');

        return redirect(route('roleAccesses.index'));
    }

    /**
     * Remove the specified RoleAccess from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $roleAccess = $this->roleAccessRepository->findWithoutFail($id);

        if (empty($roleAccess)) {
            Flash::error('Role Access not found');

            return redirect(route('roleAccesses.index'));
        }

        $this->roleAccessRepository->delete($id);

        Flash::success('Role Access deleted successfully.');

        return redirect(route('roleAccesses.index'));
    }
}
