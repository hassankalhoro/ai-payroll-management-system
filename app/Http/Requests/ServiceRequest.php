<?php

namespace App\Http\Requests;

use App\Rules\IsTimeValid;
use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
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
            $title_rules = "required|unique:services,title,{$this->service->id}";
        }
        else
        {
            $title_rules = "required|unique:services";
        }
        return [
            'title' => $title_rules,
            'sku' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
