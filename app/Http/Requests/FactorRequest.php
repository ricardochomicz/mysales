<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FactorRequest extends FormRequest
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
            'operator_id' => 'required|exists:operators,id',
            'order_type_id' => 'required|exists:order_types,id',
            'name' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe o fator de comissão.',
            'operator_id.required' => 'Selecione a operadora.',
            'order_type_id.required' => 'Selecione o tipo de pedido.',
        ];
    }
}
