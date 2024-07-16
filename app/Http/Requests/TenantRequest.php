<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TenantRequest extends FormRequest
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
            'plan_id' => 'required|integer',
            'document' => 'required|string|unique:tenants,document',
            'name' => 'required|string',
            'email' => 'required|email|unique:tenants,email',
        ];

        if ($this->method() == 'PUT') {
            // Supondo que o ID do inquilino (tenant) seja passado como parâmetro na URL


            // Ignorando o ID atual na verificação de unicidade
            $rules['document'] = 'required|unique:tenants,document,' . $this->id;
            $rules['email'] = 'required|unique:tenants,email,' . $this->id;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'plan_id.required' => 'Plano é obrigatório',
            'document.required' => 'Informe o CPF/CNPJ',
            'document.unique' => 'CPF/CNPJ já cadastrado',
            'name.required' => 'Informe o Nome',
            'email.required' => 'Informe o E-mail',
            'email.unique' => 'E-mail já cadastrado',
        ];
    }
}
