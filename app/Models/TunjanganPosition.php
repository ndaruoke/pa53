<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="TunjanganPosition",
 *      required={"tunjangan_id", "position_id", "lokal", "non_lokal", "luar_jawa", "internasional"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="tunjangan_id",
 *          description="tunjangan_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="position_id",
 *          description="position_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="lokal",
 *          description="lokal",
 *          type="number",
 *          format="double"
 *      ),
 *      @SWG\Property(
 *          property="non_lokal",
 *          description="non_lokal",
 *          type="number",
 *          format="double"
 *      ),
 *      @SWG\Property(
 *          property="luar_jawa",
 *          description="luar_jawa",
 *          type="number",
 *          format="double"
 *      ),
 *      @SWG\Property(
 *          property="internasional",
 *          description="internasional",
 *          type="number",
 *          format="double"
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
class TunjanganPosition extends Model
{
    use SoftDeletes;

    public $table = 'tunjangan_positions';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'tunjangan_id',
        'position_id',
        'lokal',
        'non_lokal',
        'luar_jawa',
        'internasional'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'tunjangan_id' => 'integer',
        'position_id' => 'integer',
        'lokal' => 'double',
        'non_lokal' => 'double',
        'luar_jawa' => 'double',
        'internasional' => 'double'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'tunjangan_id' => 'required',
        'position_id' => 'required',
        'lokal' => 'required',
        'non_lokal' => 'required',
        'luar_jawa' => 'required',
        'internasional' => 'required'
    ];

    public function positions()
    {
        return $this->hasOne('App\Models\Position', 'id','position_id');
    }


    public function tunjangans()
    {
        return $this->hasOne('App\Models\Tunjangan', 'id','tunjangan_id');
    }

}
