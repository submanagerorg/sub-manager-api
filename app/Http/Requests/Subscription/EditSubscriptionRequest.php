<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class EditSubscriptionRequest extends FormRequest
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
        $currentDate =  now()->format('Y-m-d');

        return [
            // 'name' => ['nullable','string', 'max:30'],
            // 'url' => ['nullable','string', 'url'],
            'currency' => ['nullable', 'string', 'exists:currencies,code'],
            'amount' => ['nullable', 'numeric', 'min:0', 'max:100000'],
            'start_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after:start_date',  "after_or_equal:{$currentDate}"],
            'description' => ['nullable', 'string', 'max:100'],
        ];
    }
}
