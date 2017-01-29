<?php

namespace App\Models;

use DB;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="TimesheetInsentif",
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
class TimesheetInsentif extends Model
{
    use SoftDeletes;

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
    public $table = 'timesheet_insentif';
    public $fillable = [
        'timesheet_id',
        'project_id',
        'date',
        'value',
        'keterangan',
        'status'
    ];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'timesheet_id' => 'integer',
        'project_id' => 'integer',
        'value' => 'decimal',
        'keterangan' => 'string',
        'status' => 'integer'
    ];
    protected $appends = ['approval'];

    public function timesheets()
    {
        return $this->hasOne('App\Models\Timesheet');
    }

    public function getApprovalAttribute()
    {
        $approval_ts = DB::select(DB::raw('select sequence_id,DATE_FORMAT(approval_histories.moderated_at, \'%d-%m-%Y %H:%i:%p\') as date,
CASE 
WHEN sequence_id=0 THEN "PM"
WHEN sequence_id=1 THEN "PMO"
WHEN sequence_id=2 THEN "Finance"
END approval, 
approval_id
,note ,approval_status, 
        CASE 
        WHEN approval_status=0 THEN "Pending"
        WHEN approval_status=1 THEN "Approved"
        WHEN approval_status=2 THEN "Rejected"
        WHEN approval_status=3 THEN "Postponed"
        WHEN approval_status=4 THEN "Paid"
        WHEN approval_status=5 THEN "Onhold"
        WHEN approval_status=6 THEN "Overbudget"
        END status
from approval_histories where transaction_type = 4  
and guid = "' . $this->guid . '"
order by sequence_id'));

        $approval_ts = json_decode(json_encode($approval_ts), True);
//return response()->json( $approval_ts);


        if (!isset($approval_ts[0]['sequence_id'])) {
            $newdata = array(
                'sequence_id' => '0',
                'date' => '',
                'approval_id' => '',
                'approval' => 'PM',
               // 'name' => 'test',
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
               // 'name' => 'test',
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
              //  'name' => 'test',
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
}
