<?php

namespace App\Http\Controllers;

use App\DataTables\TunjanganRoleDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTunjanganRoleRequest;
use App\Http\Requests\UpdateTunjanganRoleRequest;
use App\Repositories\TunjanganRoleRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class TunjanganRoleController extends AppBaseController
{
    /** @var  TunjanganRoleRepository */
    private $tunjanganRoleRepository;

    public function __construct(TunjanganRoleRepository $tunjanganRoleRepo)
    {
        $this->tunjanganRoleRepository = $tunjanganRoleRepo;
    }

    /**
     * Display a listing of the TunjanganRole.
     *
     * @param TunjanganRoleDataTable $tunjanganRoleDataTable
     * @return Response
     */
    public function index(TunjanganRoleDataTable $tunjanganRoleDataTable)
    {
        return $tunjanganRoleDataTable->render('tunjangan_roles.index');
    }

    /**
     * Show the form for creating a new TunjanganRole.
     *
     * @return Response
     */
    public function create()
    {
        return view('tunjangan_roles.create');
    }

    /**
     * Store a newly created TunjanganRole in storage.
     *
     * @param CreateTunjanganRoleRequest $request
     *
     * @return Response
     */
    public function store(CreateTunjanganRoleRequest $request)
    {
        $input = $request->all();

        $tunjanganRole = $this->tunjanganRoleRepository->create($input);

        Flash::success('Tunjangan Role saved successfully.');

        return redirect(route('tunjanganRoles.index'));
    }

    /**
     * Display the specified TunjanganRole.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tunjanganRole = $this->tunjanganRoleRepository->findWithoutFail($id);

        if (empty($tunjanganRole)) {
            Flash::error('Tunjangan Role not found');

            return redirect(route('tunjanganRoles.index'));
        }

        return view('tunjangan_roles.show')->with('tunjanganRole', $tunjanganRole);
    }

    /**
     * Show the form for editing the specified TunjanganRole.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tunjanganRole = $this->tunjanganRoleRepository->findWithoutFail($id);

        if (empty($tunjanganRole)) {
            Flash::error('Tunjangan Role not found');

            return redirect(route('tunjanganRoles.index'));
        }

        return view('tunjangan_roles.edit')->with('tunjanganRole', $tunjanganRole);
    }

    /**
     * Update the specified TunjanganRole in storage.
     *
     * @param  int              $id
     * @param UpdateTunjanganRoleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTunjanganRoleRequest $request)
    {
        $tunjanganRole = $this->tunjanganRoleRepository->findWithoutFail($id);

        if (empty($tunjanganRole)) {
            Flash::error('Tunjangan Role not found');

            return redirect(route('tunjanganRoles.index'));
        }

        $tunjanganRole = $this->tunjanganRoleRepository->update($request->all(), $id);

        Flash::success('Tunjangan Role updated successfully.');

        return redirect(route('tunjanganRoles.index'));
    }

    /**
     * Remove the specified TunjanganRole from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tunjanganRole = $this->tunjanganRoleRepository->findWithoutFail($id);

        if (empty($tunjanganRole)) {
            Flash::error('Tunjangan Role not found');

            return redirect(route('tunjanganRoles.index'));
        }

        $this->tunjanganRoleRepository->delete($id);

        Flash::success('Tunjangan Role deleted successfully.');

        return redirect(route('tunjanganRoles.index'));
    }
}
