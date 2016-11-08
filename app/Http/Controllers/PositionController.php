<?php

namespace App\Http\Controllers;

use App\DataTables\PositionDataTable;
use App\Http\Requests;
use App\Http\Requests\CreatePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Repositories\PositionRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Constant;

class PositionController extends AppBaseController
{
    /** @var  PositionRepository */
    private $positionRepository;

    public function __construct(PositionRepository $positionRepo)
    {
        $this->middleware('auth');
        $this->positionRepository = $positionRepo;
    }

    /**
     * Display a listing of the Position.
     *
     * @param PositionDataTable $positionDataTable
     * @return Response
     */
    public function index(PositionDataTable $positionDataTable)
    {
        return $positionDataTable->render('positions.index');
    }

    /**
     * Show the form for creating a new Position.
     *
     * @return Response
     */
    public function create()
    {
        $statuses = [''=>''] +Constant::pluck('name', 'id')->all();
        return view('positions.create',compact('statuses'));
    }

    /**
     * Store a newly created Position in storage.
     *
     * @param CreatePositionRequest $request
     *
     * @return Response
     */
    public function store(CreatePositionRequest $request)
    {
        $input = $request->all();

        $position = $this->positionRepository->create($input);

        Flash::success('Position saved successfully.');

        return redirect(route('positions.index'));
    }

    /**
     * Display the specified Position.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $position = $this->positionRepository->with('statuses')->findWithoutFail($id);

        if (empty($position)) {
            Flash::error('Position not found');

            return redirect(route('positions.index'));
        }

        return view('positions.show')->with('position', $position);
    }

    /**
     * Show the form for editing the specified Position.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $position = $this->positionRepository->findWithoutFail($id);

        if (empty($position)) {
            Flash::error('Position not found');

            return redirect(route('positions.index'));
        }

        $statuses = [''=>''] +Constant::pluck('name', 'id')->all();
        return view('positions.edit',compact('position','statuses'));
    }

    /**
     * Update the specified Position in storage.
     *
     * @param  int              $id
     * @param UpdatePositionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePositionRequest $request)
    {
        $position = $this->positionRepository->findWithoutFail($id);

        if (empty($position)) {
            Flash::error('Position not found');

            return redirect(route('positions.index'));
        }

        $position = $this->positionRepository->update($request->all(), $id);

        Flash::success('Position updated successfully.');

        return redirect(route('positions.index'));
    }

    /**
     * Remove the specified Position from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $position = $this->positionRepository->findWithoutFail($id);

        if (empty($position)) {
            Flash::error('Position not found');

            return redirect(route('positions.index'));
        }

        $this->positionRepository->delete($id);

        Flash::success('Position deleted successfully.');

        return redirect(route('positions.index'));
    }
}
