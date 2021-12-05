<?php

namespace App\Http\Requests\site;

use App\Http\Requests\BaseFormRequest;

class UsersSiteFormRequest extends BaseFormRequest
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
                'name'      => 'required|min:4|max:240',
                'email'     => 'required|email:rfc,dns|unique:userssite,email',
                'password'  => 'required|min:8|confirmed',
                'fone'      => 'required|min:11|unique:userssite,fone',
                'building'  => 'required'
            ];
        }
    }

    public function messages()
    {

        $msgRequired        = 'campo é obrigatório';
        $msgMin4            = 'campo deve ter pelo menos 4 caracteres';
        $msgMin8            = 'campo deve ter pelo menos 8 caracteres';
        $msgMax             = 'campo máximo até 240 ';
        $msgFoneMin         = 'deve ter pelo menos 11 dígitos';
        $msgFoneUnique      = 'já existe cadastro com esse celular: '.$this->fone;
        $msgEmail           = 'o e-mail deve ser um email válido';
        $msgEmailUnique     = 'já existe cadastro com esse email: '.$this->email;
        $msgPassword        = 'senhas devem ser iguais';

        return [
            'name.required'                     => $msgRequired,
            'name.min'                          => $msgMin4,
            'name.max'                          => $msgMax,
            'email.required'                    => $msgRequired,
            'email.email'                       => $msgEmail,
            'email.unique'                      => $msgEmailUnique,
            'password.required'                 => $msgRequired,
            'password.min'                      => $msgMin8,
            'password.confirmed'                => $msgPassword,
            'password_confirmation.required'    => $msgRequired,
            'fone.required'                     => $msgRequired,
            'fone.min'                          => $msgFoneMin,
            'fone.unique'                       => $msgFoneUnique,
            'building.required'                 => 'selecione uma empresa'
        ];

    }

    public function filters()
    {
        return [
            'name' => 'capitalize',
            'fone' => 'digit'
        ];
    }
}
