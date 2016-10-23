<?php

namespace App\Repositories;

use App\Models\AccessModule;
use InfyOm\Generator\Common\BaseRepository;

class AccessModuleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AccessModule::class;
    }
}
