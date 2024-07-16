<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProtocolRequest extends FormRequest
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
            'client_id' => 'required',
            'tag_id' => 'required',
            'number' => 'required',
            'operator' => 'required',
            'description' => 'required'
        ];

        if($this->status == 2 || $this->status == 3){
            $rules['answer'] = 'required';
            $rules['closure'] = 'required';
        }

        return $rules;
    }

    public function messages(): array{
        return [
            'client_id.required' => 'Selecione o cliente.',
            'tag_id.required' => 'Selecione o tipo do protocolo.',
            'number.required' => 'Informe o número do protocolo.',
            'operator.required' => 'Selecione uma operadora',
            'description.required' => 'Informe a descrição do protocolo.',
            'answer.required' => 'Informe a resposta do protocolo.',
            'closure.required' => 'Informe a data.'
        ];
    }
}
