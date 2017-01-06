<?php

namespace App\DataTables;

use App\Models\Project;
use Form;
use Yajra\Datatables\Services\DataTable;
class ProjectDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->collection($this->query())
            ->addColumn('action', 'projects.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $projects = Project::query();

        $projects = Project::with(['users','departments'])->get();

        return $this->applyScopes($projects);
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
            'project_name' => ['name' => 'project_name', 'data' => 'project_name'],
            'tunjangan_list' => ['name' => 'tunjangan_list', 'data' => 'tunjangan_list'],
            'budget' => ['name' => 'budget', 'data' => 'budget'],
            'code' => ['name' => 'code', 'data' => 'code'],
            'claimable' => ['name' => 'claimable', 'data' => 'claimable'],
            'department_id' => ['name' => 'departments.name', 'data' => 'departments.name'],
            'pm_user_id' => ['name' => 'users.name', 'data' => 'users.name']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'projects';
    }
}
