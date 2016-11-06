<?php

namespace App\DataTables;

use App\Models\TimesheetDetail;
use Form;
use Yajra\Datatables\Services\DataTable;

class TimesheetDetailDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'timesheet_details.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $timesheetDetails = TimesheetDetail::query();

        return $this->applyScopes($timesheetDetails);
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
            'lokasi' => ['name' => 'lokasi', 'data' => 'lokasi'],
            'activity' => ['name' => 'activity', 'data' => 'activity'],
            'date' => ['name' => 'date', 'data' => 'date'],
            'start_time' => ['name' => 'start_time', 'data' => 'start_time'],
            'end_time' => ['name' => 'end_time', 'data' => 'end_time'],
            'timesheet_id' => ['name' => 'timesheet_id', 'data' => 'timesheet_id'],
            'leave_id' => ['name' => 'leave_id', 'data' => 'leave_id'],
            'project_id' => ['name' => 'project_id', 'data' => 'project_id']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'timesheetDetails';
    }
}
