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

	protected $appends = ['total','monthname','status','link','approval'];


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

    public static function getapprovalmoderation($approvalId, $approvalStatus)
    {
        $result = Timesheet::getwaitingname($approvalId, $approvalStatus);

        foreach($result as $r)
        {
            $r->count = Timesheet::getapprovalcount($r->user_id, $r->approval_id, $approvalStatus);
            $r->insentif = Timesheet::gettotaltunjangan($r->user_id, $r->approval_id, $approvalStatus);
        }
        
        return $result;
    }

    public static function getwaitingname($approvalId, $approvalStatus)
    {
        $result = DB::table('timesheet_details')
            ->select('approval_histories.user_id','users.name', 'approval_histories.approval_id', 'approval_histories.approval_status')
            ->join('approval_histories','approval_histories.transaction_id','timesheet_details.id')
            ->join('users','users.id','approval_histories.user_id')
            ->where('approval_histories.approval_id','=',$approvalId)
            ->where('approval_histories.approval_status','=',$approvalStatus)
            ->where('transaction_type','=', 2)
            ->groupBy('user_id')
            ->get();
   
        return $result;
    }

    

    public static function getapprovalcount($userId, $approvalId, $approvalStatus)
    {
        $result = DB::select( DB::raw("
            select count(*) AS count_approval
                FROM approval_histories ah1 LEFT JOIN approval_histories ah2
                ON (ah1.transaction_id = ah2.transaction_id AND ah1.sequence_id < ah2.sequence_id)
                JOIN timesheet_details ON timesheet_details.id = ah1.transaction_id
                WHERE ah2.transaction_id IS NULL
                AND ah1.transaction_type = 2 
                AND ah1.approval_status = :approvalStatus
                AND ah1.approval_id = :approvalId
                AND ah1.user_id = :userId
            "), array(
                'approvalId' => $approvalId,
                'userId' => $userId,
                'approvalStatus' => $approvalStatus
            )
        );
        if(!isset($result))
        {
            return 0;
        } else {
            return $result[0]->count_approval;
        }
    }

    public static function gettotaltunjangan($userId, $approvalId, $approvalStatus)
    {
        return  Timesheet::getTotalInsentif($userId, $approvalId, $approvalStatus) + 
                Timesheet::getTotalTransport($userId, $approvalId, $approvalStatus);
    }

    public static function getTotalInsentif($userId, $approvalId, $approvalStatus)
	{
        $insentif = DB::table('timesheet_insentif')
                    ->join('approval_histories','approval_histories.transaction_id','timesheet_insentif.id')
                    ->where('approval_histories.approval_id','=',$approvalId)
                    ->where('approval_histories.user_id','=',$userId)
                    ->where('approval_histories.approval_status','=',$approvalStatus)
                    ->whereIn('transaction_type',[2,4]) //insentif dan bantuan perumahan
                    ->pluck('timesheet_insentif.value')->sum();

        return $insentif;
	}

    public static function getTotalTransport($userId, $approvalId, $approvalStatus)
	{
		$transport = DB::table('timesheet_transport')
                    ->join('approval_histories','approval_histories.transaction_id','timesheet_transport.id')
                    ->where('approval_histories.approval_id','=',$approvalId)
                    ->where('approval_histories.user_id','=',$userId)
                    ->where('approval_histories.approval_status','=',$approvalStatus)
                    ->where('transaction_type','=',3) //adcost
                    ->pluck('timesheet_transport.value')->sum();

        return $transport;
	} 
}
