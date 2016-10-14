<?php

namespace App\Repositories;

use App\Models\Sequence;
use InfyOm\Generator\Common\BaseRepository;

class SequenceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'level',
        'date',
        'role_id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Sequence::class;
    }
}
