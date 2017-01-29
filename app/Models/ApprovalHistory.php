<?php

namespace App\Models;

use Eloquent as Model;
use Hootlex\Moderation\Moderatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use Carbon\Carbon;

/**
 * @SWG\Definition(
 *      definition="ApprovalHistory",
 *      required={"note", "sequence_id", "transaction_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="note",
 *          description="note",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="sequence_id",
 *          description="sequence_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="transaction_id",
 *          description="transaction_id",
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
 *          property="approval_id",
 *          description="approval_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="transaction_type",
 *          description="transaction_type",
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
class ApprovalHistory extends Model
{
    use SoftDeletes;

    use Auditable;

    use Moderatable;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'note' => 'required',
        'sequence_id' => 'required',
        'transaction_id' => 'required',
        'transaction_type' => 'required',
        'user_id' => 'required',
        'approval_note' => 'required'
    ];
    public $table = 'approval_histories';
    public $fillable = [
        'date',
        'note',
        'sequence_id',
        'transaction_id',
        'transaction_type',
        'user_id',
        'approval_id',
        'group_approval_id',
        'approval_note'
    ];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'note' => 'string',
        'sequence_id' => 'integer',
        'transaction_id' => 'integer',
        'user_id' => 'integer',
        'approval_id' => 'integer',
        'group_approval_id' => 'integer',
        'transaction_type' => 'integer',
        'approval_note' => 'string',
        'guid' => 'string'
    ];

    public function timesheets()
    {
        return $this->hasOne('App\Models\Timesheet', 'id', 'transaction_id');
    }

    public function leaves()
    {
        return $this->hasOne('App\Models\Leave', 'id', 'transaction_id');
    }

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function approvers()
    {
        return $this->hasOne('App\Models\User', 'id', 'approval_id');
    }

    public function approvalstatuses()
    {
        return $this->hasOne('App\Models\Constant', 'value', 'approval_status')->where('category', '=', 'Moderation');
    }

    public function transactiontypes()
    {
        return $this->hasOne('App\Models\Constant', 'value', 'transaction_type')->where('category', '=', 'TransactionType');
    }

    public function getDateAttribute($date)
    {
        $cDate = Carbon::parse($date)->toDateString();
        return $cDate;
    }


}
