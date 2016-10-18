<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * @SWG\Definition(
 *      definition="TimesheetDetail",
 *      required={"lokasi", "activity", "date", "start_time", "end_time", "timesheet_id", "leave_id", "project_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="lokasi",
 *          description="lokasi",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="activity",
 *          description="activity",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="date",
 *          description="date",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="start_time",
 *          description="start_time",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="end_time",
 *          description="end_time",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="timesheet_id",
 *          description="timesheet_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="leave_id",
 *          description="leave_id",
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
class TimesheetDetail extends Model
{
    use Auditable;
    use SoftDeletes;

    public $table = 'timesheet_details';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'lokasi',
        'activity',
        'date',
        'start_time',
        'end_time',
        'timesheet_id',
        'leave_id',
        'project_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'lokasi' => 'string',
        'activity' => 'string',
        'timesheet_id' => 'integer',
        'leave_id' => 'integer',
        'project_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'lokasi' => 'required',
        'activity' => 'required',
        'date' => 'required',
        'start_time' => 'required',
        'end_time' => 'required',
        'timesheet_id' => 'required',
        'leave_id' => 'required',
        'project_id' => 'required'
    ];

    
}
