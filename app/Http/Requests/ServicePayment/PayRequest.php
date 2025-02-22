<?php

namespace App\Http\Requests\ServicePayment;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayRequest extends FormRequest
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
            'service_name' => ['required', 'string', 'exists:services,name', 'max:191'],
            'variation_code' => ['required', 'string', 'max:50'],
            'phone_number' => ['required', 'digits:11'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:191'],
            'smartcard_number' => [
                'string', 'max:50',
                Rule::when(
                    !collect(Service::getServiceClass(strtolower(request('service_name')))->getUniqueRequiredFields())
                        ->pluck('name')
                        ->contains('smartcard_number'),
                    ['nullable'], // If smartcard_number is NOT required, make it nullable
                    ['required'] // Otherwise, it's required
                ),
            ],
            'subscription_type' => ['required', 'string', 'in:new,renew'],
            'auto_renew' => ['required', 'boolean'],
            'is_tracking_disabled' => ['required', 'boolean']
        ];
    }
}
