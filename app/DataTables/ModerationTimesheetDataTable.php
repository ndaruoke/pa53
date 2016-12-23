<?php

namespace App\DataTables;

use Auth;
use App\Models\Timesheet;
use Form;
use Yajra\Datatables\Services\DataTable;
use Illuminate\Support\Str;

class ModerationTimesheetDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
            $request = $_REQUEST;
            return $this->datatables
            ->collection($this->query())
            ->filter(function ($instance) use ($request) {
                if (array_key_exists('year', $request)){
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return Str::contains($row['year'], $request['year']) ? true : false;
                    });
                }
                if (array_key_exists('month', $request)){
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return Str::contains($row['month'], $request['month']) ? true : false;
                    });
                }
                if (array_key_exists('periode', $request)){
                    $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                        return Str::contains($row['periode'], $request['periode']) ? true : false;
                    });
                }
            })
            ->addColumn('action', 'Timesheets.moderation_datatables_actions')
            ->make(true);    
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $user = Auth::user();
        
        $timesheets = Timesheet::with(['users'])->where('approval_id', $user->id)->get();

        return $this->applyScopes($timesheets);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        $html = $this->builder()
            ->columns($this->getColumns())
            ->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'dom' => 'Bfrtip',
                'scrollX' => true,
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',

                             'pdf',
                         ],
                    ],
                    'colvis'
                ],

            ])         
            ;

            
            return $html;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'nama' => ['name' => 'name', 'data' => 'users.name'],
            'jumlah_pengajuan_pa' => ['name' => 'total', 'data' => 'total'],
            'jumlah_pengajuan_tunjangan' => ['name' => 'totaltunjangan', 'data' => 'totaltunjangan'],
            'periode' => ['visible' => false,'name' => 'periode', 'data' => 'periode'],
            'month' => ['visible' => false,'name' => 'month', 'data' => 'month'],
            'year' => ['visible' => false,'name' => 'year', 'data' => 'year']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Timesheets';
    }
}
