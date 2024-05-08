<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DashboardTotalSummaryRequest extends FormRequest
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
            'currency' => ['required','string', Rule::in($this->getAcceptedCurrencies())]
        ];
    }

    /**
     * The validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'currency.required' => 'Please provide an accepted currency for this request.'
        ];
    }

    /**
     * Get accepted currencies
     *
     * @return array
     */
    private function getAcceptedCurrencies(): array {
        return Currency::pluck('code')->toArray();
    }
}
