<?php

namespace App\Repositories;

use App\Models\TunjanganRole;
use InfyOm\Generator\Common\BaseRepository;

class TunjanganRoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tunjangan_id',
        'role_id',
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
        return TunjanganRole::class;
    }
}
