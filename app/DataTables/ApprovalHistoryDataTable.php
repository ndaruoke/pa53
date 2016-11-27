<?php

namespace App\DataTables;

use App\Models\ApprovalHistory;
use Form;
use Yajra\Datatables\Services\DataTable;

class ApprovalHistoryDataTable extends DataTable
{

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->collection($this->query())
            ->addColumn('action', 'approval_histories.datatables_actions')
            ->make(true);
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $approvalHistories = ApprovalHistory::with(['timesheets','users','approvers','leaves','approvalstatuses'])->get();

        return $this->applyScopes($approvalHistories);
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
            'date' => ['name' => 'date', 'data' => 'date'],
            'note' => ['name' => 'note', 'data' => 'note'],
            'sequence' => ['name' => 'sequence_id', 'data' => 'sequence_id'],
            'transaction type' => ['name' => 'transaction_type', 'data' => 'transaction_type'],
            'transaction id' => ['name' => 'transaction_id', 'data' => 'transaction_id'],
            'user' => ['name' => 'users.name', 'data' => 'users.name'],
            'approver' => ['name' => 'approvers.name', 'data' => 'approvers.name'],
            'approval status' => ['name' => 'approvalstatuses.name', 'data' => 'approvalstatuses.name']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'approvalHistories';
    }
}
