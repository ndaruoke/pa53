<?php

namespace App\Repositories;

use App\Models\Audit;
use InfyOm\Generator\Common\BaseRepository;

class AuditRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'type',
        'auditable_id',
        'auditable_type',
        'old',
        'new',
        'user_id',
        'route',
        'ip_address',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Audit::class;
    }
}

