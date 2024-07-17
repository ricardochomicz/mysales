<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'activate' => 'nullable'
        ];

        $status_id = $this->get('status_id'); // ou $this->get('status_id');

        if ($status_id == 3 || $status_id == 4) {
            $rules['activate'] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'activate.required' => 'Informe a data de ativação.'
        ];
    }
}
