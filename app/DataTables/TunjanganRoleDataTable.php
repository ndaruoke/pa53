<?php

namespace App\DataTables;

use App\Models\TunjanganRole;
use Form;
use Yajra\Datatables\Services\DataTable;

class TunjanganRoleDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', 'tunjangan_roles.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $tunjanganRoles = TunjanganRole::query();

        return $this->applyScopes($tunjanganRoles);
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
            'tunjangan_id' => ['name' => 'tunjangan_id', 'data' => 'tunjangan_id'],
            'role_id' => ['name' => 'role_id', 'data' => 'role_id'],
            'lokal' => ['name' => 'lokal', 'data' => 'lokal'],
            'non_lokal' => ['name' => 'non_lokal', 'data' => 'non_lokal'],
            'luar_jawa' => ['name' => 'luar_jawa', 'data' => 'luar_jawa'],
            'internasional' => ['name' => 'internasional', 'data' => 'internasional']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'tunjanganRoles';
    }
}
