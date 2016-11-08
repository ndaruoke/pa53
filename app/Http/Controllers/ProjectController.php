<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Tunjangan;
use App\Models\User;
use App\Models\Project;
use App\Models\Department;
use App\Models\TunjanganProject;
use App\Models\ProjectMember;
use DB;

class ProjectController extends AppBaseController
{
    /** @var  ProjectRepository */
    private $projectRepository;

    public function __construct(ProjectRepository $projectRepo, UserRepository $userRepo)
    {
        $this->middleware('auth');
        $this->projectRepository = $projectRepo;
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the Project.
     *
     * @param ProjectDataTable $projectDataTable
     * @return Response
     */
    public function index(ProjectDataTable $projectDataTable)
    {
        return $projectDataTable->render('projects.index');
    }

    /**
     * Show the form for creating a new Project.
     *
     * @return Response
     */
    public function create()
    {
        $tunjangan = Tunjangan::pluck('name', 'id')->all();
        $user = User::pluck('name', 'id')->all();
        $department = Department::pluck('name', 'id')->all();
        return view('projects.create',compact('tunjangan','user','department'));
    }

    /**
     * Store a newly created Project in storage.
     *
     * @param CreateProjectRequest $request
     *
     * @return Response
     */
    public function store(CreateProjectRequest $request)
    {
        $input = $request->all();
        // return $request->tunjangan;

        $project = $this->projectRepository->create($input);
        //$project = Project::create($input);
        if($request->member!=null){
            $insert = array();
            foreach ($request->member as $m) {
                $insert[] = [
                    'project_id' => $project->id,
                    'user_id' => $m
                ];
            }
        }
        DB::table('project_members')->insert($insert);

        if($request->tunjangan!=null){
            $insert = array();
            foreach ($request->tunjangan as $m) {
                $insert[] = [
                    'project_id' => $project->id,
                    'tunjangan_id' => $m
                ];
            }
            DB::table('tunjangan_projects')->insert($insert);
        }

        Flash::success('Project saved successfully.');

        return redirect(route('projects.index'));
    }

    /**
     * Display the specified Project.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $project = $this->projectRepository->with('users')->with('departments')->findWithoutFail($id);

        if (empty($project)) {
            Flash::error('Project not found');

            return redirect(route('projects.index'));
        }

        return view('projects.show')->with('project', $project);
    }

    /**
     * Show the form for editing the specified Project.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $project = $this->projectRepository->findWithoutFail($id);

        if (empty($project)) {
            Flash::error('Project not found');

            return redirect(route('projects.index'));
        }

        $tunjangan = Tunjangan::pluck('name', 'id')->all();
        $user = User::pluck('name', 'id')->all();

        $selected_tunjangan = TunjanganProject::where('project_id','=',$id)->with('tunjangan')->get();
        $selected_member = ProjectMember::where('project_id','=',$id)->with('user')->get();
        $department = Department::pluck('name', 'id')->all();
        // return $selected_tunjangan;
        return view('projects.edit',compact('project','tunjangan','user','department','selected_tunjangan','selected_member'));
    }

    /**
     * Update the specified Project in storage.
     *
     * @param  int              $id
     * @param UpdateProjectRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProjectRequest $request)
    {
        $project = $this->projectRepository->findWithoutFail($id);

        if (empty($project)) {
            Flash::error('Project not found');

            return redirect(route('projects.index'));
        }

        $project = $this->projectRepository->update($request->all(), $id);

        // Update member n tunjangan Project
        if($request->member!=null){
            //delete previous data to prevent duplicate
            DB::table('project_members')->where('project_id', $id)->delete();
            $insert = array();
            foreach ($request->member as $m) {
                $insert[] = [
                    'project_id' => $project->id,
                    'user_id' => $m
                ];
            }
            DB::table('project_members')->insert($insert);
        }


        if($request->tunjangan!=null){
            //delete previous data to prevent duplicate
            DB::table('tunjangan_projects')->where('project_id', $id)->delete();
            $insert = array();
            foreach ($request->tunjangan as $m) {
                $insert[] = [
                    'project_id' => $project->id,
                    'tunjangan_id' => $m
                ];
            }
            DB::table('tunjangan_projects')->insert($insert);
        }

        Flash::success('Project updated successfully.');

        return redirect(route('projects.index'));
    }

    /**
     * Remove the specified Project from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $project = $this->projectRepository->findWithoutFail($id);

        if (empty($project)) {
            Flash::error('Project not found');

            return redirect(route('projects.index'));
        }

        $this->projectRepository->delete($id);

        Flash::success('Project deleted successfully.');

        return redirect(route('projects.index'));
    }
}
