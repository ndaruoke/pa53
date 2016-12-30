<?php

namespace App\Http\Requests;

use App\Models\UserLeave;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserLeaveRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return UserLeave::$rules;
    }
}
