<?php

namespace App\Http\Requests\ServicePayment;

use Illuminate\Foundation\Http\FormRequest;

class VerifySmartCardNumberRequest extends FormRequest
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
            'service_name' => ['required', 'string', 'exists:services,name'],
            'smartcard_number' =>['required', 'string', 'max:50']
        ];
    }
}
