<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class TpAffiliatesFormRequest extends FormRequest
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
        if ($this->method() === "POST") {
            return [
                'name' => 'required|min:4|max:240',
            ];
        }else if ($this->method() === "PUT") {
            return [
                'name' => 'required|min:4|max:240',
            ];
        }
    }

    public function messages()
    {
        $msgRequired = 'campo é obrigatório';
        $msgMin4 = 'campo deve ter pelo menos 4 caracteres';
        $msgMax = 'campo máximo até 240 ';

        return [
            'name.required' => $msgRequired,
            'name.min'      => $msgMin4,
            'name.max'      => $msgMax,
        ];
    }
}
