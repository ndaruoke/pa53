<?php

namespace App\DataTables;

use App\Models\TimesheetTransport;
use Form;
use Yajra\Datatables\Services\DataTable;

class TimesheetTransportDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'timesheet_transports.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $timesheetTransports = TimesheetTransport::query();

        return $this->applyScopes($timesheetTransports);
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
                'scrollX' => false,
                'buttons' => [
                    'print',
                    'reset',
                    'reload',
                    [
                         'extend'  => 'collection',
                         'text'    => '<i class="fa fa-download"></i> Export',
                         'buttons' => [
                             'csv',
                             'excel',
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
            'timesheet_id' => ['name' => 'timesheet_id', 'data' => 'timesheet_id'],
            'project_id' => ['name' => 'project_id', 'data' => 'project_id'],
            'date' => ['name' => 'date', 'data' => 'date'],
            'value' => ['name' => 'value', 'data' => 'value'],
            'keterangan' => ['name' => 'keterangan', 'data' => 'keterangan'],
            'status' => ['name' => 'status', 'data' => 'status']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'timesheetTransports';
    }
}
