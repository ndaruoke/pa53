<?php

namespace App\DataTables;

use App\Models\User;
use App\Models\Role;
use Form;
use Yajra\Datatables\Services\DataTable;

class UserDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->collection($this->query())
            ->addColumn('action', 'users.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $users = User::with(['roles','positions','departments'])->get();
        return $this->applyScopes($users);
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
            ->addAction(['width' => '12%'])
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
            'nik' => ['name' => 'nik', 'data' => 'nik'],
            /**
            'email' => ['name' => 'email', 'data' => 'email'],
            'nama_rekening' => ['name' => 'nama_rekening', 'data' => 'nama_rekening'],
            'rekening' => ['name' => 'rekening', 'data' => 'rekening'],
            'bank' => ['name' => 'bank', 'data' => 'bank'],
            'cabang' => ['name' => 'cabang', 'data' => 'cabang'],
             * **/
            'name' => ['name' => 'name', 'data' => 'name'],
            'role' => ['name' => 'roles.name', 'data' => 'roles.name'],
            'department' => ['name' => 'departments.name', 'data' => 'departments.name'],
            'position' => ['name' => 'positions.name', 'data' => 'positions.name']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users';
    }
}
