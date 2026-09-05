<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeratecardRequest extends FormRequest
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
        return [
            'employee_id' =>  [
                'required',
                'numeric',
                Rule::unique('employeeratecards')->where(function ($query) {
                    return $query->where('employee_id', $this->employee_id)->where('year', $this->year)->where('month', $this->month);
                })->ignore($this->id),
            ],
            'rate' => "required",
            'year' => "required|numeric",
            'month' => "required",
        ];
    }
}
