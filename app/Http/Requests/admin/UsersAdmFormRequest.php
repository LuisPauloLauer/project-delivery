<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsersAdmFormRequest extends FormRequest
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
                'tpuser'    => 'required',
                'name'      => 'required|min:4|max:240',
                'cpf'       => 'required|cpf',
                'birth'     => 'required|date',
                'sex'       => 'required',
                'fone'      => 'required|min:11',
                'email'     => 'required|email:rfc,dns|unique:usersadm,email',
                'password'  => 'required|min:8|confirmed',
                'imagen'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
        }else if ($this->method() === "PUT") {

            if(!empty($this->password)){

                return [
                    'tpuser'    => 'required',
                    'name'      => 'required|min:4|max:240',
                    'sex'       => 'required',
                    'fone'      => 'required|min:11',
                    'email'     => Rule::unique('usersadm', 'email')->where(function ($query) {
                        return $query->where('id', '<>', $this->iduseradm);
                    }),
                    'password'  => 'min:8|confirmed',
                    'imagen'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];

            } else {

                return [
                    'tpuser'    => 'required',
                    'name'      => 'required|min:4|max:240',
                    'sex'       => 'required',
                    'fone'      => 'required|min:11',
                    'email'     => Rule::unique('usersadm', 'email')->where(function ($query) {
                        return $query->where('id', '<>', $this->iduseradm);
                    }),
                    'imagen'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];

            }
        }
    }

    public function messages()
    {
        $msgRequired        = 'campo é obrigatório';
        $msgMin4            = 'campo deve ter pelo menos 4 caracteres';
        $msgMin8            = 'campo deve ter pelo menos 8 caracteres';
        $msgMax             = 'campo máximo até 240 ';
        $msgDate            = 'data é inválida';
        $msgFone            = 'fone inválido';
        $msgEmail           = 'o e-mail deve ser um email válido';
        $msgEmailUnique     = 'já existe cadastro com esse email: '.$this->email;
        $msgCPF             = 'CPF é inválido';
        $msgPassword        = 'senhas devem ser iguais';
        $msgImage           = 'Arquivo deve ser do tipo imagen';
        $msgMimes           = 'Imagen deve ser do tipo: jpeg, png, jpg, gif, svg';

        return [
            'tpuser.required'       => $msgRequired,
            'name.required'         => $msgRequired,
            'name.min'              => $msgMin4,
            'name.max'              => $msgMax,
            'cpf.required'          => $msgRequired,
            'cpf.cpf'               => $msgCPF,
            'birth.required'        => $msgRequired,
            'birth.date'            => $msgDate,
            'sex.required'          => $msgRequired,
            'fone.required'         => $msgRequired,
            'fone.min'              => $msgFone,
            'email.required'        => $msgRequired,
            'email.email'           => $msgEmail,
            'email.unique'          => $msgEmailUnique,
            'password.required'     => $msgRequired,
            'password.min'          => $msgMin8,
            'password.confirmed'    => $msgPassword,
            'imagen.image'          => $msgImage,
            'imagen.mimes'          => $msgMimes,
        ];

    }
}
