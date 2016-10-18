<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * @SWG\Definition(
 *      definition="TunjanganRole",
 *      required={"tunjangan_id", "role_id", "value"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="tunjangan_id",
 *          description="tunjangan_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="role_id",
 *          description="role_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="value",
 *          description="value",
 *          type="number",
 *          format="double"
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
class TunjanganRole extends Model
{
    use Auditable;
    use SoftDeletes;

    public $table = 'tunjangan_roles';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'tunjangan_id',
        'role_id',
        'value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'tunjangan_id' => 'integer',
        'role_id' => 'integer',
        'value' => 'double'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tunjangan_id' => 'required',
        'role_id' => 'required',
        'value' => 'required'
    ];

    
}
