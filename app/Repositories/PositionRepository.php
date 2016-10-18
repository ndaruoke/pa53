<?php

namespace App\Repositories;

use App\Models\Position;
use InfyOm\Generator\Common\BaseRepository;

class PositionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'hierarchy',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Position::class;
    }
}
