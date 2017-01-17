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

        foreach ($tunjanganPositions as $r) {
            $r->lokal_currency = "Rp ". number_format($r->lokal, 0 , ',' , '.' );
            $r->non_lokal_currency = "Rp ". number_format($r->non_lokal, 0 , ',' , '.' );
            $r->luar_jawa_currency = "Rp ". number_format($r->luar_jawa, 0 , ',' , '.' );
            $r->internasional_currency = "Rp ". number_format($r->internasional, 0 , ',' , '.' );
        }

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
            'lokal' => ['name' => 'lokal_currency', 'data' => 'lokal_currency'],
            'non_lokal' => ['name' => 'non_lokal_currency', 'data' => 'non_lokal_currency'],
            'luar_jawa' => ['name' => 'luar_jawa_currency', 'data' => 'luar_jawa_currency'],
            'internasional' => ['name' => 'internasional_currency', 'data' => 'internasional_currency']
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
