<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class StoresFormRequest extends FormRequest
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

            if(!empty($this->fone2)){
                return [
                    'affiliate'         => 'required',
                    'segment'           => 'required',
                    'name'              => 'required|min:5|max:240',
                    'zip_code'          => 'required',
                    'street'            => 'required|max:240',
                    'number'            => 'required|numeric',
                    'district'          => 'required|max:240',
                    'complement'        => 'max:240',
                    'fone1'             => 'required|min:11',
                    'fone2'             => 'min:11',
                    'email'             => 'required|email:rfc,dns',
                    'description'       => 'required',
                    'url_site'          => 'required|url',
                ];
            } else {
                return [
                    'affiliate'         => 'required',
                    'segment'           => 'required',
                    'name'              => 'required|min:5|max:240',
                    'zip_code'          => 'required',
                    'street'            => 'required|max:240',
                    'number'            => 'required|numeric',
                    'district'          => 'required|max:240',
                    'complement'        => 'max:240',
                    'fone1'             => 'required|min:11',
                    'email'             => 'required|email:rfc,dns',
                    'description'       => 'required',
                    'url_site'          => 'required|url',
                ];
            }

        }else if ($this->method() === "PUT") {
            if(!empty($this->fone2)){
                return [
                    'name'              => 'required|min:5|max:240',
                    'zip_code'          => 'required',
                    'street'            => 'required|max:240',
                    'number'            => 'required|numeric',
                    'district'          => 'required|max:240',
                    'complement'        => 'max:240',
                    'fone1'             => 'required|min:11',
                    'fone2'             => 'min:11',
                    'email'             => 'required|email:rfc,dns',
                    'description'       => 'required',
                    'url_site'          => 'required|url',
                ];
            } else {
                return [
                    'name'              => 'required|min:5|max:240',
                    'zip_code'          => 'required',
                    'street'            => 'required|max:240',
                    'number'            => 'required|numeric',
                    'district'          => 'required|max:240',
                    'complement'        => 'max:240',
                    'fone1'             => 'required|min:11',
                    'email'             => 'required|email:rfc,dns',
                    'description'       => 'required',
                    'url_site'          => 'required|url',
                ];
            }


        }
    }

    public function messages()
    {
        $msgRequired    = 'campo ?? obrigat??rio';
        $msgMin5        = 'campo deve ter pelo menos 5 caracteres';
        $msgMax         = 'campo m??ximo at?? 240 ';
        $msgNumeric     = 's?? n??meros';
        $msgFone        = 'fone inv??lido';
        $msgEmail       = 'o e-mail deve ser um email v??lido';
        $msgUrl         = 'A url deve ser uma url v??lida';
        //$msgImage       = 'Arquivo deve ser do tipo imagen';
        //$msgMimes       = 'Imagen deve ser do tipo: jpeg, png, jpg, gif, svg';

        return [
            'affiliate.required'        => $msgRequired,
            'segment.required'          => $msgRequired,
            'name.required'             => $msgRequired,
            'name.min'                  => $msgMin5,
            'name.max'                  => $msgMax,
            'zip_code.required'         => $msgRequired,
            'street.required'           => $msgRequired,
            'street.max'                => $msgMax,
            'number.required'           => $msgRequired,
            'number.numeric'            => $msgNumeric,
            'district.required'         => $msgRequired,
            'fone1.required'            => $msgRequired,
            'fone1.min'                 => $msgFone,
            'fone2.min'                 => $msgFone,
            'email.required'            => $msgRequired,
            'email.email'               => $msgEmail,
            'description.required'      => $msgRequired,
            'url_site.required'         => $msgRequired,
            'url_site.url'              => $msgUrl,
            //'imagen.required'           => $msgRequired,
            //'imagen.image'              => $msgImage,
            //'imagen.mimes'              => $msgMimes,
        ];
    }

}
