<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'operator_id' => 'required|exists:operators,id',
            'name' => 'required|string',
            'price' => 'required',
        ];
    }

    public function messages():array
    {
        return [
            'category_id.required' => 'Categoria é obrigatória.',
            'operator_id.required' => 'Operadora é obrigatório.',
            'name.required' => 'Informe o nome do produto',
            'price.required' => 'Informe o valor do produto'
        ];
    }
}
