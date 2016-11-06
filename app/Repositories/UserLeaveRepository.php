<?php

namespace App\Repositories;

use App\Models\UserLeave;
use InfyOm\Generator\Common\BaseRepository;

class UserLeaveRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'leave_count',
        'leave_used',
        'expire_date',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return UserLeave::class;
    }
}
