<?php

namespace App\Http\Controllers;

use App\DataTables\AccessModuleDataTable;
use App\Http\Requests\CreateAccessModuleRequest;
use App\Http\Requests\UpdateAccessModuleRequest;
use App\Repositories\AccessModuleRepository;
use Flash;
use Response;

class AccessModuleController extends AppBaseController
{
    /** @var  AccessModuleRepository */
    private $accessModuleRepository;

    public function __construct(AccessModuleRepository $accessModuleRepo)
    {
        $this->middleware('auth');
        $this->accessModuleRepository = $accessModuleRepo;
    }

    /**
     * Display a listing of the AccessModule.
     *
     * @param AccessModuleDataTable $accessModuleDataTable
     * @return Response
     */
    public function index(AccessModuleDataTable $accessModuleDataTable)
    {
        return $accessModuleDataTable->render('access_modules.index');
    }

    /**
     * Show the form for creating a new AccessModule.
     *
     * @return Response
     */
    public function create()
    {
        return view('access_modules.create');
    }

    /**
     * Store a newly created AccessModule in storage.
     *
     * @param CreateAccessModuleRequest $request
     *
     * @return Response
     */
    public function store(CreateAccessModuleRequest $request)
    {
        $input = $request->all();

        $accessModule = $this->accessModuleRepository->create($input);

        Flash::success('Access Module saved successfully.');

        return redirect(route('accessModules.index'));
    }

    /**
     * Display the specified AccessModule.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $accessModule = $this->accessModuleRepository->findWithoutFail($id);

        if (empty($accessModule)) {
            Flash::error('Access Module not found');

            return redirect(route('accessModules.index'));
        }

        return view('access_modules.show')->with('accessModule', $accessModule);
    }

    /**
     * Show the form for editing the specified AccessModule.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $accessModule = $this->accessModuleRepository->findWithoutFail($id);

        if (empty($accessModule)) {
            Flash::error('Access Module not found');

            return redirect(route('accessModules.index'));
        }

        return view('access_modules.edit')->with('accessModule', $accessModule);
    }

    /**
     * Update the specified AccessModule in storage.
     *
     * @param  int $id
     * @param UpdateAccessModuleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAccessModuleRequest $request)
    {
        $accessModule = $this->accessModuleRepository->findWithoutFail($id);

        if (empty($accessModule)) {
            Flash::error('Access Module not found');

            return redirect(route('accessModules.index'));
        }

        $accessModule = $this->accessModuleRepository->update($request->all(), $id);

        Flash::success('Access Module updated successfully.');

        return redirect(route('accessModules.index'));
    }

    /**
     * Remove the specified AccessModule from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $accessModule = $this->accessModuleRepository->findWithoutFail($id);

        if (empty($accessModule)) {
            Flash::error('Access Module not found');

            return redirect(route('accessModules.index'));
        }

        $this->accessModuleRepository->delete($id);

        Flash::success('Access Module deleted successfully.');

        return redirect(route('accessModules.index'));
    }
}
