<?php

namespace App\Repositories;

use App\Models\TunjanganProject;
use InfyOm\Generator\Common\BaseRepository;

class TunjanganProjectRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'project_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TunjanganProject::class;
    }
}
