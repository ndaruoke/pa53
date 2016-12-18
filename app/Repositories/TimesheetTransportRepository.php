<?php

namespace App\Repositories;

use App\Models\TimesheetTransport;
use InfyOm\Generator\Common\BaseRepository;

class TimesheetTransportRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'timesheet_id',
        'project_id',
        'date',
        'value',
        'keterangan',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TimesheetTransport::class;
    }
}
