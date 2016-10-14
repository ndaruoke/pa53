<?php

namespace App\Repositories;

use App\Models\Leave;
use InfyOm\Generator\Common\BaseRepository;

class LeaveRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'start_date',
        'end_date',
        'approval_id',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Leave::class;
    }
}
