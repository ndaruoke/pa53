<?php

namespace App\Models;


use Eloquent as Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use OwenIt\Auditing\Auditable;
use DB;


/** * @SWG\Definition( *      definition="Timesheet", *      required={""}, *      @SWG\Property( *          property="id", *          description="id", *          type="integer", *          format="int32" *      ), *      @SWG\Property( *          property="user_id", *          description="user_id", *          type="integer", *          format="int32" *      ), *      @SWG\Property( *          property="periode", *          description="periode", *          type="string", *          format="date-time" *      ), *      @SWG\Property( *          property="created_at", *          description="created_at", *          type="string", *          format="date-time" *      ), *      @SWG\Property( *          property="updated_at", *          description="updated_at", *          type="string", *          format="date-time" *      ) * ) */

class Timesheet extends Model
{
	
	use SoftDeletes;
	
	
	use Auditable;
	
	
	public $table = 'timesheets';
	
	
	
	protected $dates = ['deleted_at'];
	
	
	
	public $fillable = [
	'id',
	'month',
	'week',
	'year',
	'user_id',
	'periode',
   // 'action'
	];
	
	
	
	/**     * The attributes that should be casted to native types.     *     * @var array     */
	
	protected $appends = ['total','monthname','status','link'];
	
	
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
	
	protected $casts = [
	'user_id' => 'integer'
	];
	
	
	
	/**     * Validation rules     *     * @var array     */
	
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


	
	
	// 	public function getPeriodeAttribute($date)
	// 	{
		
		// 		$cDate = \Carbon\Carbon::parse($date)->toDateString();
		
		// 		return $cDate;
		
		//
	
	
	
}

