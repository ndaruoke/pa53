<?php

namespace App\Models;

use DB;
use Eloquent as Model;
use Hootlex\Moderation\Moderatable;
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
 *      ),
 *      @SWG\Property(
 *          property="approval_id",
 *          description="approval_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="approval_status",
 *          description="approval_status",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="moderated_at",
 *          description="moderated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class TimesheetDetail extends Model
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
        'lokasi' => 'required',
        'activity' => 'required',
        'date' => 'required',
        'start_time' => 'required',
        'end_time' => 'required',
        'timesheet_id' => 'required',
        'leave_id' => 'required',
        'project_id' => 'required'
    ];
    public $table = 'timesheet_details';
    public $fillable = [
        'id',
        'lokasi',
        'activity',
        'date',
        'start_time',
        'end_time',
        'timesheet_id',
        'leave_id',
        'project_id',
        'approval_id',
        'approval_status',
        'moderated_at'
    ];
    protected $dates = ['deleted_at'];
    protected $appends = ['status'];
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
        'project_id' => 'integer',
        'approval_id' => 'integer',
        'approval_status' => 'integer'
    ];

    public function getStatusAttribute()
    {
        $approval_ts = DB::select(DB::raw('select approval_histories.moderated_at as date,sequence_id,
CASE 
WHEN sequence_id=0 THEN "PM"
WHEN sequence_id=1 THEN "PMO"
WHEN sequence_id=2 THEN "Finance"
END approval, 
approval_id
,users.name,note ,approval_status

, 
        CASE 
        WHEN approval_status=0 THEN "Pending"
        WHEN approval_status=1 THEN "Approved"
        WHEN approval_status=2 THEN "Rejected"
        WHEN approval_status=3 THEN "Postponed"
        WHEN approval_status=4 THEN "Paid"
        WHEN approval_status=5 THEN "Onhold"
        WHEN approval_status=6 THEN "Overbudget"
        END status
from approval_histories,users where transaction_type = 2 
and transaction_id = ' . $this->id . '
and users.id = approval_histories.approval_id order by sequence_id'));

        $approval_ts = json_decode(json_encode($approval_ts), True);
//return response()->json( $approval_ts);


        if (!isset($approval_ts[0]['sequence_id'])) {
            $newdata = array(
                'sequence_id' => '0',
                'date' => '',
                'approval_id' => '',
                'approval' => 'PM',
                'name' => 'test',
                'approval_status' => 3,
                'status' => 'Pending'
            );
            array_push($approval_ts, $newdata);
        }
        if (!isset($approval_ts[1]['sequence_id'])) {
            $newdata = array(
                'sequence_id' => '1',
                'date' => '',
                'approval_id' => '',
                'approval' => 'PMO',
                'name' => 'test',
                'approval_status' => 3,
                'status' => 'Pending'
            );
            array_push($approval_ts, $newdata);
        }
        if (!isset($approval_ts[2]['sequence_id'])) {
            $newdata = array(
                'sequence_id' => '2',
                'date' => '',
                'approval_id' => '',
                'approval' => 'Finance',
                'name' => 'test',
                'approval_status' => 3,
                'status' => 'Pending'
            );
            array_push($approval_ts, $newdata);
        }
        $status = '<i class="fa fa-fw fa-circle" data-toggle="tooltip" title="" style="' . $this->getColor($approval_ts[0]['status']) . '" data-original-title="'. $approval_ts[0]['date'] .' ' . $approval_ts[0]['approval'] . ' ' . $approval_ts[0]['status'] . '"></i>';
        $status .= '<i class="fa fa-fw fa-circle" data-toggle="tooltip" title="" style="' . $this->getColor($approval_ts[1]['status']) . '" data-original-title="' . $approval_ts[1]['date'] .' '. $approval_ts[1]['approval'] . ' ' . $approval_ts[1]['status'] . '"></i>';
        $status .= '<i class="fa fa-fw fa-circle" data-toggle="tooltip" title="" style="' . $this->getColorFinance($approval_ts[2]['status']) . '" data-original-title="' . $approval_ts[2]['date'] .' '. $approval_ts[2]['approval'] . ' ' . $approval_ts[2]['status'] . '"></i>';

        return $status;
    }

    public function getColor($status)
    {
        if ($status == "Approved") {
            return 'color:#00a65a';
        } else if ($status == "Rejected") {
            return 'color:#dd4b39';
        } else if ($status == "Postponed") {
            return 'color:cyan';
        } 
        else if ($status == "Onhold") {
            return 'color:#dd4b39';
        }
        else if ($status == "Overbudget") {
            return 'color:#dd4b39';
        }
        else if ($status == "Paid") {
            return 'color:green';
        } else {
            return 'color:orange';
        }
    }
    public function getColorFinance($status)
    {
        if ($status == "Approved") {
            return 'color:blue';
        } else if ($status == "Rejected") {
            return 'color:#dd4b39';
        } 
        else if ($status == "Onhold") {
            return 'color:#dd4b39';
        }
        else if ($status == "Overbudget") {
            return 'color:#dd4b39';
        }
        else if ($status == "Postponed") {
            return 'color:cyan';
        } else if ($status == "Paid") {
            return 'color:green';
        } else {
            return 'color:orange';
        }
    }

    public function timesheets()
    {
        return $this->hasOne('App\Models\Timesheet');
    }
}
