<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class AddSubscriptionRequest extends FormRequest
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
            'name' => ['required','string', 'max:30'],
            'url' => ['string', 'url'],
            'currency' => ['required', 'string', 'exists:currencies,symbol'],
            'amount' => ['required', 'numeric', 'min:1', 'max:100000'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date', 'date_format:Y-m-d', 'after:start_date',  "after_or_equal:{$currentDate}"],
            'description' => ['nullable', 'string', 'max:100'],
        ];
    }
}
