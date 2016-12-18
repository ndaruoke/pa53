<?php

namespace App\DataTables;

use Auth;
use App\Models\Timesheet;
use Form;
use Yajra\Datatables\Services\DataTable;

class ModerationTimesheetDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->collection($this->query())
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
        return $this->builder()
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
                ]
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    private function getColumns()
    {
        return [
            'nama' => ['name' => 'users.name', 'data' => 'users.name'],
            'jumlah_pengajuan_pa' => ['name' => 'total', 'data' => 'total']
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
