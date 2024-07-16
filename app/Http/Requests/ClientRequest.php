<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
        $rules = [
            'document' => 'required|unique:clients,document',
            'name' => 'required',
        ];

        if ($this->method() == 'PUT') {
            // Supondo que o ID do inquilino (tenant) seja passado como parâmetro na URL

            // Ignorando o ID atual na verificação de unicidade
            $rules['document'] = 'required|unique:clients,document,' . $this->route('id');
        }

        return $rules;
    }

    public function messages():array
    {
        return [
            'document.required' => 'Informe o CNPJ do cliente',
            'name.required' => 'Informe o nome do cliente',
        ];
    }
}
