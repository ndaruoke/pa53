<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * @SWG\Definition(
 *      definition="UserLeave",
 *      required={"user_id", "leave_count", "expire_date", "status"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
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
 *          property="leave_count",
 *          description="leave_count",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="leave_count",
 *          description="leave_count",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="expire_date",
 *          description="expire_date",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
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
class UserLeave extends Model
{
    use SoftDeletes;

    use Auditable;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'leave_count' => 'required',
        'leave_used' => 'required',
        'expire_date' => 'required',
        'status' => 'required'
    ];
    public $table = 'user_leaves';
    public $fillable = [
        'user_id',
        'leave_count',
        'leave_used',
        'expire_date',
        'status'
    ];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'leave_count' => 'integer',
        'leave_used' => 'integer',
        'status' => 'integer'
    ];

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function statuses()
    {
        return $this->hasOne('App\Models\Constant', 'value', 'status')->where('category', '=', 'Status');
    }

    public function getExpireDateAttribute($date)
    {
        $cDate = \Carbon\Carbon::parse($date)->toDateString();
        return $cDate;
    }
}
