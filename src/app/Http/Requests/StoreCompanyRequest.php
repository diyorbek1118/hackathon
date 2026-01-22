<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'monthly_avg_income' => 'nullable|numeric|min:0|max:999999999999999.99',
            'monthly_avg_expense' => 'nullable|numeric|min:0|max:999999999999999.99',
            'currency' => 'nullable|string|max:10|in:UZS,USD,EUR,RUB',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Kompaniya nomi kiritilishi shart',
            'name.max' => 'Kompaniya nomi 255 belgidan oshmasligi kerak',
            'industry.max' => 'Industriya nomi 255 belgidan oshmasligi kerak',
            'monthly_avg_income.numeric' => 'O\'rtacha oylik daromad raqam bo\'lishi kerak',
            'monthly_avg_income.min' => 'O\'rtacha oylik daromad 0 dan kichik bo\'lmasligi kerak',
            'monthly_avg_income.max' => 'O\'rtacha oylik daromad juda katta',
            'monthly_avg_expense.numeric' => 'O\'rtacha oylik xarajat raqam bo\'lishi kerak',
            'monthly_avg_expense.min' => 'O\'rtacha oylik xarajat 0 dan kichik bo\'lmasligi kerak',
            'monthly_avg_expense.max' => 'O\'rtacha oylik xarajat juda katta',
            'currency.in' => 'Valyuta UZS, USD, EUR yoki RUB bo\'lishi kerak',
            'currency.max' => 'Valyuta kodi 10 belgidan oshmasligi kerak',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'kompaniya nomi',
            'industry' => 'industriya',
            'monthly_avg_income' => 'o\'rtacha oylik daromad',
            'monthly_avg_expense' => 'o\'rtacha oylik xarajat',
            'currency' => 'valyuta',
        ];
    }
}