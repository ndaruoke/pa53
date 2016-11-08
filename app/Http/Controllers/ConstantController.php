<?php

namespace App\Http\Controllers;

use App\DataTables\ConstantDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateConstantRequest;
use App\Http\Requests\UpdateConstantRequest;
use App\Repositories\ConstantRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class ConstantController extends AppBaseController
{
    /** @var  ConstantRepository */
    private $constantRepository;

    public function __construct(ConstantRepository $constantRepo)
    {
        $this->middleware('auth');
        $this->constantRepository = $constantRepo;
    }

    /**
     * Display a listing of the Constant.
     *
     * @param ConstantDataTable $constantDataTable
     * @return Response
     */
    public function index(ConstantDataTable $constantDataTable)
    {
        return $constantDataTable->render('constants.index');
    }

    /**
     * Show the form for creating a new Constant.
     *
     * @return Response
     */
    public function create()
    {
        return view('constants.create');
    }

    /**
     * Store a newly created Constant in storage.
     *
     * @param CreateConstantRequest $request
     *
     * @return Response
     */
    public function store(CreateConstantRequest $request)
    {
        $input = $request->all();

        $constant = $this->constantRepository->create($input);

        Flash::success('Constant saved successfully.');

        return redirect(route('constants.index'));
    }

    /**
     * Display the specified Constant.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $constant = $this->constantRepository->findWithoutFail($id);

        if (empty($constant)) {
            Flash::error('Constant not found');

            return redirect(route('constants.index'));
        }

        return view('constants.show')->with('constant', $constant);
    }

    /**
     * Show the form for editing the specified Constant.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $constant = $this->constantRepository->findWithoutFail($id);

        if (empty($constant)) {
            Flash::error('Constant not found');

            return redirect(route('constants.index'));
        }

        return view('constants.edit')->with('constant', $constant);
    }

    /**
     * Update the specified Constant in storage.
     *
     * @param  int              $id
     * @param UpdateConstantRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateConstantRequest $request)
    {
        $constant = $this->constantRepository->findWithoutFail($id);

        if (empty($constant)) {
            Flash::error('Constant not found');

            return redirect(route('constants.index'));
        }

        $constant = $this->constantRepository->update($request->all(), $id);

        Flash::success('Constant updated successfully.');

        return redirect(route('constants.index'));
    }

    /**
     * Remove the specified Constant from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $constant = $this->constantRepository->findWithoutFail($id);

        if (empty($constant)) {
            Flash::error('Constant not found');

            return redirect(route('constants.index'));
        }

        $this->constantRepository->delete($id);

        Flash::success('Constant deleted successfully.');

        return redirect(route('constants.index'));
    }
}
