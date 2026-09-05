<?php

namespace App\Http\Requests;

use App\Rules\IsTimeValid;
use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest
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
        if ($this->method() == 'PUT')
        {
            $email_rules = "required|unique:tenants,email,{$this->tenant->id}";
        }
        else
        {
            $email_rules = "required|unique:tenants";
        }
        return [
            'email' => $email_rules,
            'title' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
