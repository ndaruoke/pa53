<?php

namespace App\Repositories;

use App\Models\Timesheet;
use InfyOm\Generator\Common\BaseRepository;

class TimesheetRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'periode',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Timesheet::class;
    }
}
