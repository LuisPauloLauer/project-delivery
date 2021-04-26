<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class AffiliatesFormRequest extends FormRequest
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

            if($this->type_person === 'PF'){

                if(!empty($this->fone2)){
                    return [
                        'tpaffiliate'       => 'required',
                        'type_person'       => 'required',
                        'corporate_name'    => 'required|min:5|max:240',
                        'fantasy_name'      => 'required|min:5|max:240',
                        'zip_code'          => 'required',
                        'street'            => 'required|max:240',
                        'number'            => 'required|numeric',
                        'district'          => 'required|max:240',
                        'complement'        => 'max:240',
                        'fone1'             => 'required|min:11',
                        'fone2'             => 'min:11',
                        'cpf_or_cnpj'       => 'required|cpf',
                        'email'             => 'required|email:rfc,dns',
                    ];
                } else {
                    return [
                        'tpaffiliate'       => 'required',
                        'type_person'       => 'required',
                        'corporate_name'    => 'required|min:5|max:240',
                        'fantasy_name'      => 'required|min:5|max:240',
                        'zip_code'          => 'required',
                        'street'            => 'required|max:240',
                        'number'            => 'required|numeric',
                        'district'          => 'required|max:240',
                        'complement'        => 'max:240',
                        'fone1'             => 'required|min:11',
                        'cpf_or_cnpj'       => 'required|cpf',
                        'email'             => 'required|email:rfc,dns',
                    ];
                }

            } else if ($this->type_person === 'PJ'){

                if(!empty($this->fone2)){
                    return [
                        'tpaffiliate'       => 'required',
                        'type_person'       => 'required',
                        'corporate_name'    => 'required|min:5|max:240',
                        'fantasy_name'      => 'required|min:5|max:240',
                        'zip_code'          => 'required',
                        'street'            => 'required|max:240',
                        'number'            => 'required|numeric',
                        'district'          => 'required|max:240',
                        'complement'        => 'max:240',
                        'fone1'             => 'required|min:11',
                        'fone2'             => 'min:11',
                        'cpf_or_cnpj'       => 'required|cnpj',
                        'email'             => 'required|email:rfc,dns',
                    ];
                } else {
                    return [
                        'tpaffiliate'       => 'required',
                        'type_person'       => 'required',
                        'corporate_name'    => 'required|min:5|max:240',
                        'fantasy_name'      => 'required|min:5|max:240',
                        'zip_code'          => 'required',
                        'street'            => 'required|max:240',
                        'number'            => 'required|numeric',
                        'district'          => 'required|max:240',
                        'complement'        => 'max:240',
                        'fone1'             => 'required|min:11',
                        'cpf_or_cnpj'       => 'required|cnpj',
                        'email'             => 'required|email:rfc,dns',
                    ];
                }

            }

        }else if ($this->method() === "PUT") {
            if(!empty($this->fone2)){
                return [
                    'tpaffiliate'       => 'required',
                    'corporate_name'    => 'required|min:5|max:240',
                    'fantasy_name'      => 'required|min:5|max:240',
                    'zip_code'          => 'required',
                    'street'            => 'required|max:240',
                    'number'            => 'required|numeric',
                    'district'          => 'required|max:240',
                    'complement'        => 'max:240',
                    'fone1'             => 'required|min:11',
                    'fone2'             => 'min:11',
                    'email'             => 'required|email:rfc,dns',
                ];
            } else {
                return [
                    'tpaffiliate'       => 'required',
                    'corporate_name'    => 'required|min:5|max:240',
                    'fantasy_name'      => 'required|min:5|max:240',
                    'zip_code'          => 'required',
                    'street'            => 'required|max:240',
                    'number'            => 'required|numeric',
                    'district'          => 'required|max:240',
                    'complement'        => 'max:240',
                    'fone1'             => 'required|min:11',
                    'email'             => 'required|email:rfc,dns',
                ];
            }
        }
    }

    public function messages()
    {

        $msgRequired    = 'campo é obrigatório';
        $msgMin5        = 'campo deve ter pelo menos 5 caracteres';
        $msgFone        = 'fone inválido';
        $msgMax         = 'campo máximo até 240 ';
        $msgNumeric     = 'só números';
        $msgEmail       = 'o e-mail deve ser um email válido';

        if($this->type_person === 'PF'){
            $msgcpf_or_cnpj     = 'CPF é inválido';
            $cpf_or_cnpj        = 'cpf';
        }else if($this->type_person === 'PJ'){
            $msgcpf_or_cnpj     = 'CNPJ é inválido';
            $cpf_or_cnpj        = 'cnpj';
        } else {
            $msgcpf_or_cnpj     = 'campo é inválido';
            $cpf_or_cnpj        = 'cpf';
        }

        return [
            'tpaffiliate.required'          => $msgRequired,
            'type_person.required'          => $msgRequired,
            'corporate_name.required'       => $msgRequired,
            'corporate_name.min'            => $msgMin5,
            'corporate_name.max'            => $msgMax,
            'fantasy_name.required'         => $msgRequired,
            'fantasy_name.min'              => $msgMin5,
            'fantasy_name.max'              => $msgMax,
            'zip_code.required'             => $msgRequired,
            'street.required'               => $msgRequired,
            'street.max'                    => $msgMax,
            'number.required'               => $msgRequired,
            'number.numeric'                => $msgNumeric,
            'district.required'             => $msgRequired,
            'district.max'                  => $msgMax,
            'complement.max'                => $msgMax,
            'fone1.required'                => $msgRequired,
            'fone1.min'                     => $msgFone,
            'fone2.min'                     => $msgFone,
            'cpf_or_cnpj.required'          => $msgRequired,
            'cpf_or_cnpj.'.$cpf_or_cnpj     => $msgcpf_or_cnpj,
            'email.required'                => $msgRequired,
            'email.email'                   => $msgEmail,
        ];
    }
}
