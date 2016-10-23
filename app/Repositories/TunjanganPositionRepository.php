<?php

namespace App\Repositories;

use App\Models\TunjanganPosition;
use InfyOm\Generator\Common\BaseRepository;

class TunjanganPositionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tunjangan_id',
        'position_id',
        'lokal',
        'non_lokal',
        'luar_jawa',
        'internasional',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return TunjanganPosition::class;
    }
}
