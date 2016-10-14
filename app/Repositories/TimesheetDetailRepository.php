<?php

namespace App\Repositories;

use App\Models\TimesheetDetail;
use InfyOm\Generator\Common\BaseRepository;

class TimesheetDetailRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'lokasi',
        'activity',
        'date',
        'start_time',
        'end_time',
        'timesheet_id',
        'leave_id',
        'project_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TimesheetDetail::class;
    }
}
