<?php

namespace App\Repositories;

use App\Models\ApprovalHistory;
use InfyOm\Generator\Common\BaseRepository;

class ApprovalHistoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date',
        'note',
        'sequence_id',
        'timesheet_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ApprovalHistory::class;
    }
}
