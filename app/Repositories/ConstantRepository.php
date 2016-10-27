<?php

namespace App\Repositories;

use App\Models\Constant;
use InfyOm\Generator\Common\BaseRepository;

class ConstantRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'category',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Constant::class;
    }
}
