<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BanktransactionRequest extends FormRequest
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
            'transaction_date' => "required",
            'category' => "required",
            'account_id' => "required",
            'amount' => "required",
        ];
    }
}
