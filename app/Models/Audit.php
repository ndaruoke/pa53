<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Audit",
 *      required={""},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="auditable_id",
 *          description="auditable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="auditable_type",
 *          description="auditable_type",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="old",
 *          description="old",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="new",
 *          description="new",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="route",
 *          description="route",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="ip_address",
 *          description="ip_address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Audit extends Model
{
    use SoftDeletes;

    public $table = 'audits';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'type',
        'auditable_id',
        'auditable_type',
        'old',
        'new',
        'user_id',
        'route',
        'ip_address'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'type' => 'string',
        'auditable_id' => 'integer',
        'auditable_type' => 'string',
        'old' => 'string',
        'new' => 'string',
        'user_id' => 'string',
        'route' => 'string',
        'ip_address' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
