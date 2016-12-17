<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * @SWG\Definition(
 *      definition="Constant",
 *      required={"name", "category", "status"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="category",
 *          description="category",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="value",
 *          description="value",
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
class Constant extends Model
{
    use SoftDeletes;

    use Auditable;

    public $table = 'constants';
    
    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'category',
        'status',
        'value'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'category' => 'string',
        'status' => 'integer',
        'value' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'category' => 'required',
        'status' => 'required',
        'value' => 'required'
    ];

    public function leave()
    {
        return $this->belongsTo('App\Models\Leave');
    }

    public function position()
    {
        return $this->belongsTo('App\Models\Position');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function userLeave()
    {
        return $this->belongsTo('App\Models\UserLeave');
    }

    public function approvalHistory()
    {
        return $this->belongsTo('App\Models\ApprovalHistory');
    }

    public function sequence()
    {
        return $this->belongsTo('App\Models\Sequence');
    }

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
}
