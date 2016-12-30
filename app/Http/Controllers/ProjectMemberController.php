<?php

namespace App\Http\Controllers;

use App\DataTables\ProjectMemberDataTable;
use App\Http\Requests\CreateProjectMemberRequest;
use App\Http\Requests\UpdateProjectMemberRequest;
use App\Models\Project;
use App\Models\User;
use App\Repositories\ProjectMemberRepository;
use Flash;
use Response;

class ProjectMemberController extends AppBaseController
{
    /** @var  ProjectMemberRepository */
    private $projectMemberRepository;

    public function __construct(ProjectMemberRepository $projectMemberRepo)
    {
        $this->middleware('auth');
        $this->projectMemberRepository = $projectMemberRepo;
    }

    /**
     * Display a listing of the ProjectMember.
     *
     * @param ProjectMemberDataTable $projectMemberDataTable
     * @return Response
     */
    public function index(ProjectMemberDataTable $projectMemberDataTable)
    {
        return $projectMemberDataTable->render('project_members.index');
    }

    /**
     * Show the form for creating a new ProjectMember.
     *
     * @return Response
     */
    public function create()
    {
        $user = ['' => ''] + User::pluck('name', 'id')->all();
        $project = ['' => ''] + Project::pluck('project_name', 'id')->all();
        return view('project_members.create', compact('user', 'project'));
    }

    /**
     * Store a newly created ProjectMember in storage.
     *
     * @param CreateProjectMemberRequest $request
     *
     * @return Response
     */
    public function store(CreateProjectMemberRequest $request)
    {
        $input = $request->all();

        $projectMember = $this->projectMemberRepository->create($input);

        Flash::success('Project Member saved successfully.');

        return redirect(route('projectMembers.index'));
    }

    /**
     * Display the specified ProjectMember.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $projectMember = $this->projectMemberRepository->findWithoutFail($id);

        if (empty($projectMember)) {
            Flash::error('Project Member not found');

            return redirect(route('projectMembers.index'));
        }

        return view('project_members.show')->with('projectMember', $projectMember);
    }

    /**
     * Show the form for editing the specified ProjectMember.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $projectMember = $this->projectMemberRepository->findWithoutFail($id);

        if (empty($projectMember)) {
            Flash::error('Project Member not found');

            return redirect(route('projectMembers.index'));
        }

        $user = ['' => ''] + User::pluck('name', 'id')->all();
        $project = ['' => ''] + Project::pluck('project_name', 'id')->all();
        return view('project_members.edit', compact('user', 'project'));
    }

    /**
     * Update the specified ProjectMember in storage.
     *
     * @param  int $id
     * @param UpdateProjectMemberRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProjectMemberRequest $request)
    {
        $projectMember = $this->projectMemberRepository->findWithoutFail($id);

        if (empty($projectMember)) {
            Flash::error('Project Member not found');

            return redirect(route('projectMembers.index'));
        }

        $projectMember = $this->projectMemberRepository->update($request->all(), $id);

        Flash::success('Project Member updated successfully.');

        return redirect(route('projectMembers.index'));
    }

    /**
     * Remove the specified ProjectMember from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $projectMember = $this->projectMemberRepository->findWithoutFail($id);

        if (empty($projectMember)) {
            Flash::error('Project Member not found');

            return redirect(route('projectMembers.index'));
        }

        $this->projectMemberRepository->delete($id);

        Flash::success('Project Member deleted successfully.');

        return redirect(route('projectMembers.index'));
    }
}
