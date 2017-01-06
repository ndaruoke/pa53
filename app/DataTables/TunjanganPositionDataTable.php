<?php

namespace App\DataTables;

use App\Models\TunjanganPosition;
use Form;
use Yajra\Datatables\Services\DataTable;

class TunjanganPositionDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->collection($this->query())
            ->addColumn('action', 'tunjangan_positions.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $tunjanganPositions = TunjanganPosition::with(['tunjangans','positions'])->get();

        return $this->applyScopes($tunjanganPositions);
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
            'tunjangan_id' => ['name' => 'tunjangans.name', 'data' => 'tunjangans.name'],
            'position_id' => ['name' => 'positions.name', 'data' => 'positions.name'],
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
        return 'tunjanganPositions';
    }
}
