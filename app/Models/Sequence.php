<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * @SWG\Definition(
 *      definition="Sequence",
 *      required={"level", "role_id", "user_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="level",
 *          description="level",
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
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
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
class Sequence extends Model
{
    use SoftDeletes;

    use Auditable;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'level' => 'required',
        'role_id' => 'required',
        'user_id' => 'required'
    ];
    public $table = 'sequences';
    public $fillable = [
        'level',
        'date',
        'role_id',
        'user_id'
    ];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'level' => 'integer',
        'role_id' => 'integer',
        'user_id' => 'integer',
        'user_position' => 'integer',
        'transaction_type' => 'integer'
    ];

    public function roles()
    {
        return $this->hasOne('App\Models\Roles', 'id', 'role_id');
    }

    public function users()
    {
        return $this->hasOne('App\Models\Users', 'id', 'user_id');
    }

    public function userPositions()
    {
        return $this->hasOne('App\Models\Position', 'id', 'position_id');
    }

    public function transactiontypes()
    {
        return $this->hasOne('App\Models\Constant', 'value', 'transaction_type')->where('category', '=', 'TransactionType');
    }
}
