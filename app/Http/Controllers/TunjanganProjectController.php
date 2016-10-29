<?php

namespace App\Http\Controllers;

use App\DataTables\TunjanganProjectDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTunjanganProjectRequest;
use App\Http\Requests\UpdateTunjanganProjectRequest;
use App\Repositories\TunjanganProjectRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Project;

class TunjanganProjectController extends AppBaseController
{
    /** @var  TunjanganProjectRepository */
    private $tunjanganProjectRepository;

    public function __construct(TunjanganProjectRepository $tunjanganProjectRepo)
    {
        $this->tunjanganProjectRepository = $tunjanganProjectRepo;
    }

    /**
     * Display a listing of the TunjanganProject.
     *
     * @param TunjanganProjectDataTable $tunjanganProjectDataTable
     * @return Response
     */
    public function index(TunjanganProjectDataTable $tunjanganProjectDataTable)
    {
        return $tunjanganProjectDataTable->render('tunjangan_projects.index');
    }

    /**
     * Show the form for creating a new TunjanganProject.
     *
     * @return Response
     */
    public function create()
    {
        $projects = [''=>''] +Project::pluck('name', 'id')->all();
        return view('tunjangan_projects.create',compact('projects'));
    }

    /**
     * Store a newly created TunjanganProject in storage.
     *
     * @param CreateTunjanganProjectRequest $request
     *
     * @return Response
     */
    public function store(CreateTunjanganProjectRequest $request)
    {
        $input = $request->all();

        $tunjanganProject = $this->tunjanganProjectRepository->create($input);

        Flash::success('Tunjangan Project saved successfully.');

        return redirect(route('tunjanganProjects.index'));
    }

    /**
     * Display the specified TunjanganProject.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tunjanganProject = $this->tunjanganProjectRepository->findWithoutFail($id);

        if (empty($tunjanganProject)) {
            Flash::error('Tunjangan Project not found');

            return redirect(route('tunjanganProjects.index'));
        }

        return view('tunjangan_projects.show')->with('tunjanganProject', $tunjanganProject);
    }

    /**
     * Show the form for editing the specified TunjanganProject.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tunjanganProject = $this->tunjanganProjectRepository->findWithoutFail($id);

        if (empty($tunjanganProject)) {
            Flash::error('Tunjangan Project not found');

            return redirect(route('tunjanganProjects.index'));
        }

        $projects = [''=>''] +Project::pluck('name', 'id')->all();
        return view('tunjangan_projects.edit',compact('tunjanganProject','projects'));
    }

    /**
     * Update the specified TunjanganProject in storage.
     *
     * @param  int              $id
     * @param UpdateTunjanganProjectRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTunjanganProjectRequest $request)
    {
        $tunjanganProject = $this->tunjanganProjectRepository->findWithoutFail($id);

        if (empty($tunjanganProject)) {
            Flash::error('Tunjangan Project not found');

            return redirect(route('tunjanganProjects.index'));
        }

        $tunjanganProject = $this->tunjanganProjectRepository->update($request->all(), $id);

        Flash::success('Tunjangan Project updated successfully.');

        return redirect(route('tunjanganProjects.index'));
    }

    /**
     * Remove the specified TunjanganProject from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tunjanganProject = $this->tunjanganProjectRepository->findWithoutFail($id);

        if (empty($tunjanganProject)) {
            Flash::error('Tunjangan Project not found');

            return redirect(route('tunjanganProjects.index'));
        }

        $this->tunjanganProjectRepository->delete($id);

        Flash::success('Tunjangan Project deleted successfully.');

        return redirect(route('tunjanganProjects.index'));
    }
}
