<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
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
            'module' => 'required',
            'label' => 'required',
        ];
    }


    public function messages(): array
    {
        return [
            'module.required' => 'Informe o nome do módulo.',
            'label.required' => 'Informe o título do módulo.',
        ];
    }
}
