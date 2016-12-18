<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="TimesheetTransport",
 *      required={"timesheet_id", "project_id", "date", "value", "keterangan", "status"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="timesheet_id",
 *          description="timesheet_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="project_id",
 *          description="project_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="date",
 *          description="date",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="value",
 *          description="value",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="keterangan",
 *          description="keterangan",
 *          type="string"
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
class TimesheetTransport extends Model
{
    use SoftDeletes;

    public $table = 'timesheet_transports';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'timesheet_id',
        'project_id',
        'date',
        'value',
        'keterangan',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'timesheet_id' => 'integer',
        'project_id' => 'integer',
        'value' => 'string',
        'keterangan' => 'string',
        'status' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'timesheet_id' => 'required',
        'project_id' => 'required',
        'date' => 'required',
        'value' => 'required',
        'keterangan' => 'required',
        'status' => 'required'
    ];

    public function timesheets()
    {
        return $this->hasOne('App\Models\Timesheet');
    }
}
