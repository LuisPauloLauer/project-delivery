<?php

namespace App\Http\Requests\admin;

use App\Http\Requests\BaseFormRequest;
use App\User;
use Illuminate\Validation\Rule;

class UsersAdmFormRequest extends BaseFormRequest
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
                'tpuser'                => 'required',
                'name'                  => 'required|min:4|max:240',
                'cpf'                   => 'required|cpf|unique:usersadm,cpf',
                'birth'                 => 'required|date',
                'sex'                   => 'required',
                'fone'                  => 'required|min:11|celular_com_ddd',
                'email'                 => 'required|email:rfc,dns|unique:usersadm,email',
                'password'              => 'required|min:8|confirmed',
                'password_confirmation' => 'required',
                'imagen'                => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
        }else if ($this->method() === "PUT") {

            if(!empty($this->password)){

                return [
                    'tpuser'                => 'required',
                    'name'                  => 'required|min:4|max:240',
                    'sex'                   => 'required',
                    'fone'                  => 'required|min:11|celular_com_ddd',
                    'email'                 => Rule::unique('usersadm', 'email')->where(function ($query) {
                                                    return $query->where('id', '<>', $this->iduseradm);
                                                }),
                    'password'              => 'min:8|confirmed',
                    'password_confirmation' => 'required',
                    'imagen'                => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];

            } else {

                return [
                    'tpuser'    => 'required',
                    'name'      => 'required|min:4|max:240',
                    'sex'       => 'required',
                    'fone'      => 'required|min:11|celular_com_ddd',
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

        if(User::where('cpf', $this->cpf)->exists()) {
            $dataUserAdm    = User::where('cpf', $this->cpf)->first();
            $msgCPFunique   = 'CPF já existe no Usuário ID: ('.$dataUserAdm->id.')';
        } else {
            $msgCPFunique   = '';
        }

        $msgRequired        = 'campo é obrigatório';
        $msgMin4            = 'campo deve ter pelo menos 4 caracteres';
        $msgMin8            = 'campo deve ter pelo menos 8 caracteres';
        $msgMax             = 'campo máximo até 240 ';
        $msgDate            = 'data é inválida';
        $msgFoneMin         = 'deve ter pelo menos 11 dígitos';
        $msgEmail           = 'o e-mail deve ser um email válido';
        $msgEmailUnique     = 'já existe cadastro com esse email: '.$this->email;
        $msgCPFinvalid      = 'CPF é inválido';
        $msgPassword        = 'senhas devem ser iguais';
        $msgImage           = 'Arquivo deve ser do tipo imagen';
        $msgMimes           = 'Imagen deve ser do tipo: jpeg, png, jpg, gif, svg';

        return [
            'tpuser.required'                   => $msgRequired,
            'name.required'                     => $msgRequired,
            'name.min'                          => $msgMin4,
            'name.max'                          => $msgMax,
            'cpf.required'                      => $msgRequired,
            'cpf.cpf'                           => $msgCPFinvalid,
            'cpf.unique'                        => $msgCPFunique,
            'birth.required'                    => $msgRequired,
            'birth.date'                        => $msgDate,
            'sex.required'                      => $msgRequired,
            'fone.required'                     => $msgRequired,
            'fone.min'                          => $msgFoneMin,
            'email.required'                    => $msgRequired,
            'email.email'                       => $msgEmail,
            'email.unique'                      => $msgEmailUnique,
            'password.required'                 => $msgRequired,
            'password.min'                      => $msgMin8,
            'password.confirmed'                => $msgPassword,
            'password_confirmation.required'    => $msgRequired,
            'imagen.image'                      => $msgImage,
            'imagen.mimes'                      => $msgMimes,
        ];

    }

    /**
     *  Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'name' => 'capitalize',
            'cpf' => 'digit'
       ];
    }
}
