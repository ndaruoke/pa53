<?php

namespace App\Repositories;

use App\Models\ProjectMember;
use InfyOm\Generator\Common\BaseRepository;

class ProjectMemberRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'project_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProjectMember::class;
    }
}
