<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // adjust this based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required|exists:tenants,id',
            'bill_to_address' => 'required|string|max:400',
            'invoice_number' => 'filled|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'invoice_due_date' => 'required|date|after_or_equal:invoice_date',
            'description.*' => 'nullable|string|max:255', // assuming description can be nullable
            'qty.*' => 'required|min:0',
            'rate.*' => 'required|min:0',
            'amount.*' => 'required|min:0',
            'note_to_employee' => 'nullable|string|max:400',
        ];
    }
}
