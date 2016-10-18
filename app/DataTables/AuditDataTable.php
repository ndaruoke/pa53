<?php

namespace App\DataTables;

use App\Models\Audit;
use Form;
use Yajra\Datatables\Services\DataTable;

class AuditDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'audits.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $audits = Audit::query();

        return $this->applyScopes($audits);
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
            'type' => ['name' => 'type', 'data' => 'type'],
            'auditable_id' => ['name' => 'auditable_id', 'data' => 'auditable_id'],
            'auditable_type' => ['name' => 'auditable_type', 'data' => 'auditable_type'],
            'old' => ['name' => 'old', 'data' => 'old'],
            'new' => ['name' => 'new', 'data' => 'new'],
            'user_id' => ['name' => 'user_id', 'data' => 'user_id'],
            'route' => ['name' => 'route', 'data' => 'route'],
            'ip_address' => ['name' => 'ip_address', 'data' => 'ip_address']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'audits';
    }
}
