<?php

namespace App\Http\Requests\PlanPayment;

use Illuminate\Foundation\Http\FormRequest;

class InitiatePaymentRequest extends FormRequest
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
            'email' => ['required', 'string', 'email:rfc,dns', 'max:191'],
            'pricing_plan_uid' => ['required', 'string', 'exists:pricing_plans,uid'],
        ];
    }
}
