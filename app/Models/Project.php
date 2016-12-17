<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * @SWG\Definition(
 *      definition="Project",
 *      required={"project_name", "tunjangan_list", "iwo", "code", "claimable", "department_id", "pm_user_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="project_name",
 *          description="project_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="tunjangan_list",
 *          description="tunjangan_list",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="budget",
 *          description="integer",
 *          type="integer",format="int32"
 *      ),
 *      @SWG\Property(
 *          property="code",
 *          description="code",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="claimable",
 *          description="claimable",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="department_id",
 *          description="department_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="pm_user_id",
 *          description="pm_user_id",
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
class Project extends Model
{
    use SoftDeletes;

    use Auditable;

    public $table = 'projects';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'project_name',
        'tunjangan_list',
        'budget',
        'code',
        'claimable',
        'department_id',
        'pm_user_id',
        'effort_type'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'project_name' => 'string',
        'tunjangan_list' => 'string',
        'budget' => 'decimal',
        'code' => 'integer',
        'claimable' => 'integer',
        'department_id' => 'integer',
        'pm_user_id' => 'integer',
        'effort_type' => 'decimal'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'project_name' => 'required',
		
      //  'tunjangan_list' => 'required',
	//	'department_id' => 'required',
	//	
        'budget' => 'required',
        'code' => 'required',
        'claimable' => 'required',
         'pm_user_id' => 'required'
    ];

    public function departments()
    {
        return $this->hasOne('App\Models\Department', 'id','department_id');
    }

    public function users()
    {
        return $this->hasOne('App\Models\User', 'id','pm_user_id');
    }

    public function tunjanganProject()
    {
        return $this->belongsTo('App\Models\TunjanganProject');
    }

    public function efforttypes()
    {
        return $this->hasOne('App\Models\Constant', 'value','effort_type')->where('category', '=','EffortType');
    }
    
}
