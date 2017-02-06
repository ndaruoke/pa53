<?php

namespace App\DataTables;

use Auth;
use App\Models\Timesheet;
use Form;
use Yajra\Datatables\Services\DataTable;

class TimesheetReportDataTable extends DataTable
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->of($this->query())
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $request = $_REQUEST;
        if(empty($request['$approvalStatus']))
        {
            $reportType = 2;
        } else
        {
            $reportType = $request['$approvalStatus'];
        }

        $timesheets = Timesheet::getreport($reportType);

        return $timesheets;
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
            //->addAction(['width' => '10%'])
            ->ajax('')
            ->parameters([
                'dom' => 'Bfrtip',
                'scrollX' => true,
                'buttons' => [
                    'print',

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
            'id' => ['name' => 'id', 'id' => 'id'],
            'periode' => ['name' => 'periode', 'data' => 'periode'],
            'week' => ['name' => 'week', 'data' => 'week'],
            'month' => ['name' => 'monthname', 'data' => 'monthname'],
            'year' => ['name' => 'year', 'data' => 'year'],
        //    'total' => ['name' => 'total', 'data' => 'total'],
            'total' => ['name' => 'total', 'data' => 'submitted'],
            'status' => ['name' => 'status', 'data' => 'approval'],
            'detail' => ['name' => 'link', 'data' => 'link']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'timesheets';
    }
}
