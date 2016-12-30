<?php

namespace App\Http\Controllers;

use App\DataTables\HolidayDataTable;
use App\Http\Requests\CreateHolidayRequest;
use App\Http\Requests\UpdateHolidayRequest;
use App\Repositories\HolidayRepository;
use DB;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Input;
use Response;

class HolidayController extends AppBaseController
{
    /** @var  HolidayRepository */
    private $holidayRepository;

    public function __construct(HolidayRepository $holidayRepo)
    {
        $this->middleware('auth');
        $this->holidayRepository = $holidayRepo;
    }

    /**
     * Display a listing of the Holiday.
     *
     * @param HolidayDataTable $holidayDataTable
     * @return Response
     */
    public function index(HolidayDataTable $holidayDataTable)
    {
        return $holidayDataTable->render('holidays.index');
    }

    /**
     * Show the form for creating a new Holiday.
     *
     * @return Response
     */
    public function create()
    {
        return view('holidays.create');
    }

    /**
     * Store a newly created Holiday in storage.
     *
     * @param CreateHolidayRequest $request
     *
     * @return Response
     */
    public function store(CreateHolidayRequest $request)
    {
        $input = $request->all();

        $holiday = $this->holidayRepository->create($input);

        Flash::success('Holiday saved successfully.');

        return redirect(route('holidays.index'));
    }

    /**
     * Display the specified Holiday.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $holiday = $this->holidayRepository->findWithoutFail($id);

        if (empty($holiday)) {
            Flash::error('Holiday not found');

            return redirect(route('holidays.index'));
        }

        return view('holidays.show')->with('holiday', $holiday);
    }

    /**
     * Show the form for editing the specified Holiday.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $holiday = $this->holidayRepository->findWithoutFail($id);

        if (empty($holiday)) {
            Flash::error('Holiday not found');

            return redirect(route('holidays.index'));
        }

        return view('holidays.edit')->with('holiday', $holiday);
    }

    /**
     * Update the specified Holiday in storage.
     *
     * @param  int $id
     * @param UpdateHolidayRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHolidayRequest $request)
    {
        $holiday = $this->holidayRepository->findWithoutFail($id);

        if (empty($holiday)) {
            Flash::error('Holiday not found');

            return redirect(route('holidays.index'));
        }

        $holiday = $this->holidayRepository->update($request->all(), $id);

        Flash::success('Holiday updated successfully.');

        return redirect(route('holidays.index'));
    }

    /**
     * Remove the specified Holiday from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $holiday = $this->holidayRepository->findWithoutFail($id);

        if (empty($holiday)) {
            Flash::error('Holiday not found');

            return redirect(route('holidays.index'));
        }

        $this->holidayRepository->delete($id);

        Flash::success('Holiday deleted successfully.');

        return redirect(route('holidays.index'));
    }

    public function processSheet(Request $request)
    {
        if ($request->file('import_file')) {
            $path = $request->file('import_file')->getRealPath();
            $data = Excel::load($path, function ($reader) {
            })->get();
            if (!empty($data) && $data->count()) {
                foreach ($data as $key => $value) {
                    $insert[] = [
                        'name' => $value->name,
                        'date' => $value->date,
                    ];
                    //  return \Response::json($insert);
                }
                if (!empty($insert)) {
                    DB::table('holidays')->insert($insert);
                    Flash::success('Holiday updated successfully.');
                    return redirect(route('holidays.index'));
                }
                //   return \Response::json($insert);
                return back();
            }
        }
        //  return back();
    }
}