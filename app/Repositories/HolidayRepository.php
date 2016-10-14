<?php

namespace App\Repositories;

use App\Models\Holiday;
use InfyOm\Generator\Common\BaseRepository;

class HolidayRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'date',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Holiday::class;
    }
}
