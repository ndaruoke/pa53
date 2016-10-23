<?php

namespace App\Http\Controllers;

use App\DataTables\TunjanganPositionDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTunjanganPositionRequest;
use App\Http\Requests\UpdateTunjanganPositionRequest;
use App\Repositories\TunjanganPositionRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;
use App\Models\Tunjangan;
use App\Models\Position;

class TunjanganPositionController extends AppBaseController
{
    /** @var  TunjanganPositionRepository */
    private $tunjanganPositionRepository;

    public function __construct(TunjanganPositionRepository $tunjanganPositionRepo)
    {
        $this->tunjanganPositionRepository = $tunjanganPositionRepo;
    }

    /**
     * Display a listing of the TunjanganPosition.
     *
     * @param TunjanganPositionDataTable $tunjanganPositionDataTable
     * @return Response
     */
    public function index(TunjanganPositionDataTable $tunjanganPositionDataTable)
    {
        return $tunjanganPositionDataTable->render('tunjangan_positions.index');
    }

    /**
     * Show the form for creating a new TunjanganPosition.
     *
     * @return Response
     */
    public function create()
    {
        $positions = [''=>''] +Position::pluck('name', 'id')->all();
        $tunjangans = [''=>''] +Tunjangan::pluck('name', 'id')->all();
        return view('tunjangan_positions.create',compact('tunjangans','positions'));
    }

    /**
     * Store a newly created TunjanganPosition in storage.
     *
     * @param CreateTunjanganPositionRequest $request
     *
     * @return Response
     */
    public function store(CreateTunjanganPositionRequest $request)
    {
        $input = $request->all();

        $tunjanganPosition = $this->tunjanganPositionRepository->create($input);

        Flash::success('Tunjangan Position saved successfully.');

        return redirect(route('tunjanganPositions.index'));
    }

    /**
     * Display the specified TunjanganPosition.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tunjanganPosition = $this->tunjanganPositionRepository->findWithoutFail($id);

        if (empty($tunjanganPosition)) {
            Flash::error('Tunjangan Position not found');

            return redirect(route('tunjanganPositions.index'));
        }

        return view('tunjangan_positions.show')->with('tunjanganPosition', $tunjanganPosition);
    }

    /**
     * Show the form for editing the specified TunjanganPosition.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tunjanganPosition = $this->tunjanganPositionRepository->findWithoutFail($id);

        if (empty($tunjanganPosition)) {
            Flash::error('Tunjangan Position not found');

            return redirect(route('tunjanganPositions.index'));
        }

        $positions = [''=>''] +Position::pluck('name', 'id')->all();
        $tunjangans = [''=>''] +Tunjangan::pluck('name', 'id')->all();
        return view('tunjangan_positions.edit',compact('tunjanganPosition','tunjangans','positions'));
        //return view('tunjangan_positions.edit')->with('tunjanganPosition');
    }

    /**
     * Update the specified TunjanganPosition in storage.
     *
     * @param  int              $id
     * @param UpdateTunjanganPositionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTunjanganPositionRequest $request)
    {
        $tunjanganPosition = $this->tunjanganPositionRepository->findWithoutFail($id);

        if (empty($tunjanganPosition)) {
            Flash::error('Tunjangan Position not found');

            return redirect(route('tunjanganPositions.index'));
        }

        $tunjanganPosition = $this->tunjanganPositionRepository->update($request->all(), $id);

        Flash::success('Tunjangan Position updated successfully.');

        return redirect(route('tunjanganPositions.index'));
    }

    /**
     * Remove the specified TunjanganPosition from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tunjanganPosition = $this->tunjanganPositionRepository->findWithoutFail($id);

        if (empty($tunjanganPosition)) {
            Flash::error('Tunjangan Position not found');

            return redirect(route('tunjanganPositions.index'));
        }

        $this->tunjanganPositionRepository->delete($id);

        Flash::success('Tunjangan Position deleted successfully.');

        return redirect(route('tunjanganPositions.index'));
    }
}
