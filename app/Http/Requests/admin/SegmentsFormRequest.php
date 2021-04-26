<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class SegmentsFormRequest extends FormRequest
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
              //  'imagen'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
        }else if ($this->method() === "PUT") {
            return [
                'name' => 'required|min:4|max:240',
             //   'imagen'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];
        }
    }

    public function messages()
    {
        $msgRequired = 'campo é obrigatório';
        $msgMin4 = 'campo deve ter pelo menos 4 caracteres';
        $msgMax = 'campo máximo até 240 ';
        $msgImage = 'Arquivo deve ser do tipo imagen';
        $msgMimes = 'Imagen deve ser do tipo: jpeg, png, jpg, gif, svg';

        return [
            'name.required' => $msgRequired,
            'name.min'      => $msgMin4,
            'name.max'      => $msgMax,
          //  'imagen.image'  => $msgImage,
          //  'imagen.mimes'  => $msgMimes,
        ];
    }
}
