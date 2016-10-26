<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="User",
 *      required={"nik", "nama_rekening", "rekening", "bank", "cabang", "name", "role"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="nik",
 *          description="nik",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="nama_rekening",
 *          description="nama_rekening",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="rekening",
 *          description="rekening",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="bank",
 *          description="bank",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="cabang",
 *          description="cabang",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="password",
 *          description="password",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="role",
 *          description="role",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="remember_token",
 *          description="remember_token",
 *          type="string"
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
class User extends Model
{
    use SoftDeletes;

    public $table = 'users';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'nik',
        'email',
        'nama_rekening',
        'rekening',
        'bank',
        'cabang',
        'name',
        'password',
        'role',
        'position',
        'department',
        'image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nik' => 'string',
        'email' => 'string',
        'nama_rekening' => 'string',
        'rekening' => 'string',
        'bank' => 'string',
        'cabang' => 'string',
        'name' => 'string',
        'password' => 'string',
        'role' => 'integer',
        'position' => 'integer',
        'department' => 'integer',
        'remember_token' => 'string',
        'image' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nik' => '',
        'nama_rekening' => '',
        'rekening' => '',
        'bank' => '',
        'cabang' => '',
        'name' => 'required',
        'role' => ''
    ];

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id','role');
    }

    public function position()
    {
        return $this->hasOne('App\Models\Position', 'id','position');
    }

    public function department()
    {
        return $this->hasOne('App\Models\Department', 'id','department');
    }

    public function leave()
    {
        return $this->belongsTo('App\Models\Leave');
    }

    public function audit()
    {
        return $this->belongsTo('App\Models\Audit');
    }

    
}
