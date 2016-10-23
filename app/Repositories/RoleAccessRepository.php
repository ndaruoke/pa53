<?php

namespace App\Repositories;

use App\Models\RoleAccess;
use InfyOm\Generator\Common\BaseRepository;

class RoleAccessRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'role_id',
        'module_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return RoleAccess::class;
    }
}
