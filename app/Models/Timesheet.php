<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use Hootlex\Moderation\Moderatable;
use DB;

/**
 * @SWG\Definition(
 *      definition="Timesheet",
 *      required={""},
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
 *          property="periode",
 *          description="periode",
 *          type="string",
 *          format="date-time"
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
class Timesheet extends Model
{
    use SoftDeletes;

    use Auditable;

    use Moderatable;

    public $table = 'timesheets';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
		'id',
		'month',
		'week',
		'year',
        'user_id',
        'periode',
        'approval_id',
        'approval_status',
		'moderated_at'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'approval_id' => 'integer',
        'approval_status' => 'integer'
    ];

	protected $appends = ['total','monthname','status','link','approval','totaltunjangan','totalinsentif','totaltransport'];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id','user_id');
    }

    public function approvalHistory()
    {
        return $this->belongsTo('App\Models\ApprovalHistory');
    }

    // public function getPeriodeAttribute($date)
    // {
    //     $cDate = \Carbon\Carbon::parse($date)->toDateString();
    //     return $cDate;
    // }

	public function getTotalAttribute()
	{
		return DB::table('timesheet_details')->where('timesheet_id','=',$this->id)->count();
	}

    public function getTotalInsentifAttribute()
	{
        $insentif = DB::table('timesheet_insentif')->where('timesheet_id','=',$this->id);
        if($insentif->count()>0)
        {
            return $insentif->select('value')->sum();
        }
		else 
        {
            return 0;
        }
	}

    public function getTotalTransportAttribute()
	{
		$transport = DB::table('timesheet_insentif')->where('timesheet_id','=',$this->id);
        if($transport->count()>0)
        {
            return $transport->select('value')->sum();
        }
		else 
        {
            return 0;
        }
	}

    public function getTotalTunjanganAttribute()
    {
        return $this->getTotalInsentifAttribute() + $this->getTotalTransportAttribute();
    }

	public function getMonthnameAttribute()
	{
		return date("F", mktime(0, 0, 0, $this->month, 10));	
	}

	public function getStatusAttribute()
	{
		return $this->action;
	}

	public function getLinkAttribute()
	{
		return '<a href="timesheet/show/'.$this->id.'" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-eye-open"></i>';	
	}

    public function getApprovalAttribute()
	{
		 $liststatus = DB::select(DB::raw("select if(approval_histories.approval_status = 0,'approved','rejected')status , count(approval_histories.approval_status)total from timesheet_details, approval_histories where timesheet_details.id = approval_histories.transaction_id and timesheet_details.timesheet_id = ".$this->id." and approval_histories.transaction_type = 12  group by status"));
         $statuses = '';
         foreach($liststatus as $status){
             if($status->status==='approved'){
               //  
                $statuses = $statuses.' '.'<i class="fa fa-fw fa-circle" data-toggle="tooltip" title="" style="color:#00a65a" data-original-title="'.$status->status.' ('.$status->total.')"></i>';
             } else {
                 $statuses = $statuses.' '.'<i class="fa fa-fw fa-circle" data-toggle="tooltip" title="" style="color:#dd4b39" data-original-title="'.$status->status.' ('.$status->total.')"></i>';
             }
             
         }
         if ( $statuses === ''){
             // 'waiting';
             $statuses = '<i class="fa fa-fw fa-circle" data-toggle="tooltip" title="" style="color:#f39c12" data-original-title="Verivikasi"></i>';
         }
         return $statuses;
	}

    public function timesheetdetails()
    {
        return $this->hasMany('App\Models\TimesheetDetail');
    }

    public function timesheetinsentifs()
    {
        return $this->hasMany('App\Models\TimesheetInsentif');
    }

    public function timesheettransports()
    {
        return $this->hasMany('App\Models\TimesheetTransport');
    }
           
}
