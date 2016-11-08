<?php

namespace App\Http\Controllers;

use App\DataTables\TunjanganDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateTunjanganRequest;
use App\Http\Requests\UpdateTunjanganRequest;
use App\Repositories\TunjanganRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class TunjanganController extends AppBaseController
{
    /** @var  TunjanganRepository */
    private $tunjanganRepository;

    public function __construct(TunjanganRepository $tunjanganRepo)
    {
        $this->middleware('auth');
        $this->tunjanganRepository = $tunjanganRepo;
    }

    /**
     * Display a listing of the Tunjangan.
     *
     * @param TunjanganDataTable $tunjanganDataTable
     * @return Response
     */
    public function index(TunjanganDataTable $tunjanganDataTable)
    {
        return $tunjanganDataTable->render('tunjangans.index');
    }

    /**
     * Show the form for creating a new Tunjangan.
     *
     * @return Response
     */
    public function create()
    {
        return view('tunjangans.create');
    }

    /**
     * Store a newly created Tunjangan in storage.
     *
     * @param CreateTunjanganRequest $request
     *
     * @return Response
     */
    public function store(CreateTunjanganRequest $request)
    {
        $input = $request->all();

        $tunjangan = $this->tunjanganRepository->create($input);

        Flash::success('Tunjangan saved successfully.');

        return redirect(route('tunjangans.index'));
    }

    /**
     * Display the specified Tunjangan.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tunjangan = $this->tunjanganRepository->findWithoutFail($id);

        if (empty($tunjangan)) {
            Flash::error('Tunjangan not found');

            return redirect(route('tunjangans.index'));
        }

        return view('tunjangans.show')->with('tunjangan', $tunjangan);
    }

    /**
     * Show the form for editing the specified Tunjangan.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tunjangan = $this->tunjanganRepository->findWithoutFail($id);

        if (empty($tunjangan)) {
            Flash::error('Tunjangan not found');

            return redirect(route('tunjangans.index'));
        }

        return view('tunjangans.edit')->with('tunjangan', $tunjangan);
    }

    /**
     * Update the specified Tunjangan in storage.
     *
     * @param  int              $id
     * @param UpdateTunjanganRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTunjanganRequest $request)
    {
        $tunjangan = $this->tunjanganRepository->findWithoutFail($id);

        if (empty($tunjangan)) {
            Flash::error('Tunjangan not found');

            return redirect(route('tunjangans.index'));
        }

        $tunjangan = $this->tunjanganRepository->update($request->all(), $id);

        Flash::success('Tunjangan updated successfully.');

        return redirect(route('tunjangans.index'));
    }

    /**
     * Remove the specified Tunjangan from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tunjangan = $this->tunjanganRepository->findWithoutFail($id);

        if (empty($tunjangan)) {
            Flash::error('Tunjangan not found');

            return redirect(route('tunjangans.index'));
        }

        $this->tunjanganRepository->delete($id);

        Flash::success('Tunjangan deleted successfully.');

        return redirect(route('tunjangans.index'));
    }
}
