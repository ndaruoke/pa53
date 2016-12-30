<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;

/**
 * @SWG\Definition(
 *      definition="Position",
 *      required={"name", "description", "hierarchy", "status"},
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
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="hierarchy",
 *          description="hierarchy",
 *          type="integer",
 *          format="int32"
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
class Position extends Model
{
    use SoftDeletes;

    use Auditable;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'description' => 'required',
        'hierarchy' => 'required',
        'status' => 'required'
    ];
    public $table = 'positions';
    public $fillable = [
        'name',
        'description',
        'hierarchy',
        'status'
    ];
    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'hierarchy' => 'integer',
        'status' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tunjanganPosition()
    {
        return $this->belongsTo('App\Models\TunjanganPosition');
    }

    public function sequence()
    {
        return $this->belongsTo('App\Models\Sequence');
    }

    public function statuses()
    {
        return $this->hasOne('App\Models\Constant', 'value', 'status')->where('category', '=', 'Status');
    }
}
