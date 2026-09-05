<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SiteAccountsRequest extends FormRequest
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
            $title_rules = "required|unique:site_accounts,account_number,{$this->siteaccount}";
        }
        else
        {
            $title_rules = "required|unique:site_accounts";
        }
        return [
            'account_number' => $title_rules,
            'bank_name' => "required",
            'account_title' => "required",
        ];
    }
}
