<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * @SWG\Definition(
 *      definition="Leave",
 *      required={"start_date", "end_date", "approval_id", "status"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="start_date",
 *          description="start_date",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="end_date",
 *          description="end_date",
 *          type="string",
 *          format="date-time"
 *      ),
 *     @SWG\Property(
 *          property="note",
 *          description="note",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="approval_id",
 *          description="approval_id",
 *          type="integer",
 *          format="int32"
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
class Leave extends Model
{
    use SoftDeletes;

    use Auditable;

    public $table = 'leaves';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'start_date',
        'end_date',
        'note',
        'user_id',
        'approval_id',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'approval_id' => 'integer',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'start_date' => 'required',
        'end_date' => 'required',
        'note' => 'required'/**,
        'user_id' => 'required',
        'approval_id' => 'required',
        'status' => 'required'**/
    ];

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id','user_id');
    }

    public function approvals()
    {
        return $this->hasOne('App\Models\User', 'id','approval_id');
    }

    public function statuses()
    {
        return $this->hasOne('App\Models\Constant', 'id','status');
    }

    public function getStartDateAttribute($date)
    {
        $cDate = \Carbon\Carbon::parse($date)->toDateString();
        return $cDate;
    }

    public function getEndDateAttribute($date)
    {
        $cDate = \Carbon\Carbon::parse($date)->toDateString();
        return $cDate;
    }
}
