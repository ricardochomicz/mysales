<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpportunityRequest extends FormRequest
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
            'client_id' => 'required',
            'operator' => 'required',
            'order_type' => 'required',
            'forecast' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Selecione o cliente',
            'operator.required' => 'Selecione uma operadora.',
            'order_type.required' => 'Selecione o tipo',
            'forecast.required' => 'Informe a data de previsão.'
        ];


    }
}
