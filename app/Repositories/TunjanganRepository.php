<?php

namespace App\Repositories;

use App\Models\Tunjangan;
use InfyOm\Generator\Common\BaseRepository;

class TunjanganRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Tunjangan::class;
    }
}
