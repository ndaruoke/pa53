<?php

namespace App\Repositories;

use App\Models\TimesheetInsentif;
use InfyOm\Generator\Common\BaseRepository;

class TimesheetInsentifRepository extends BaseRepository
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
        return TimesheetInsentif::class;
    }
}
