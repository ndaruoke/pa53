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
        if(empty($request['reportType']))
        {
            $reportType = array(1);
        } else
        {
            $reportType = array($request['reportType']);
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
        $result = array();
        $request = $_REQUEST;

        if($request['reportType'] != 1)
        {
            $result = [
                'status' => ['name' => 'moderation_name', 'data' => 'moderation_name'],
                'budget' => ['name' => 'budget', 'data' => 'budget']
            ];
        }

        $normalResult = [
            'nik' => ['name' => 'nik', 'id' => 'nik'],
            'email' => ['name' => 'email', 'data' => 'email'],
            'display_name' => ['name' => 'user_name', 'data' => 'user_name'],
            'responsibilities' => ['name' => 'position_name', 'data' => 'position_name'],
            'iwo' => ['name' => 'code', 'data' => 'code'],
            'project_name' => ['name' => 'project_name', 'data' => 'project_name'],
            'summary_taskname' => ['name' => 'activity', 'data' => 'activity'],
            'task_name' => ['name' => 'activity_detail', 'data' => 'activity_detail'],

            'total_work' => ['name' => 'hour', 'data' => 'hour'],
            'year' => ['name' => 'year', 'data' => 'year'],
            'month' => ['name' => 'month', 'data' => 'month'],
            'effort_type' => ['name' => 'effort_name', 'data' => 'effort_name'],
            'task_type' => ['name' => 'is_billable', 'data' => 'is_billable'],
            'week_in_month' => ['name' => 'week', 'data' => 'week']

        ];

        $result = array_merge($result,$normalResult);

        return $result;
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
