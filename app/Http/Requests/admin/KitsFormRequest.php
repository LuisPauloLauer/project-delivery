<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KitsFormRequest extends FormRequest
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

            if(!empty($this->unit_promotion_price)){

                if( (!empty($this->id_pdv_store)) && (!empty($this->codigo_pdv_store)) && (!empty($this->codigo_barras_pdv_store)) ){
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'id_pdv_store'              => Rule::unique('kits', 'id_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'codigo_pdv_store'          => Rule::unique('kits', 'codigo_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'codigo_barras_pdv_store'   => Rule::unique('kits', 'codigo_barras_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->id_pdv_store)){
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'id_pdv_store'              => Rule::unique('kits', 'id_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->codigo_pdv_store)){
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'codigo_pdv_store'          => Rule::unique('kits', 'codigo_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->codigo_barras_pdv_store)){
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'codigo_barras_pdv_store'   => Rule::unique('kits', 'codigo_barras_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else {
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                }

            } else {

                if( (!empty($this->id_pdv_store)) && (!empty($this->codigo_pdv_store)) && (!empty($this->codigo_barras_pdv_store)) ){
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'id_pdv_store'              => Rule::unique('kits', 'id_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'codigo_pdv_store'          => Rule::unique('kits', 'codigo_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'codigo_barras_pdv_store'   => Rule::unique('kits', 'codigo_barras_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->id_pdv_store)){
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'id_pdv_store'              => Rule::unique('kits', 'id_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->codigo_pdv_store)){
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'codigo_pdv_store'          => Rule::unique('kits', 'codigo_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->codigo_barras_pdv_store)){
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'codigo_barras_pdv_store'   => Rule::unique('kits', 'codigo_barras_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else {
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                }
            }

        } else if ($this->method() === "PUT") {

            if(!empty($this->unit_promotion_price)){

                if( (!empty($this->id_pdv_store)) && (!empty($this->codigo_pdv_store)) && (!empty($this->codigo_barras_pdv_store)) ){
                    return [
                        'category_product'          => 'required',
                        'id_pdv_store'              => Rule::unique('kits', 'id_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'codigo_pdv_store'          => Rule::unique('kits', 'codigo_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'codigo_barras_pdv_store'   => Rule::unique('kits', 'codigo_barras_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->id_pdv_store)){
                    return [
                        'category_product'          => 'required',
                        'id_pdv_store'              => Rule::unique('kits', 'id_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->codigo_pdv_store)){
                    return [
                        'category_product'          => 'required',
                        'codigo_pdv_store'          => Rule::unique('kits', 'codigo_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->codigo_barras_pdv_store)){
                    return [
                        'category_product'          => 'required',
                        'codigo_barras_pdv_store'   => Rule::unique('kits', 'codigo_barras_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else {
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                }

            } else {

                if( (!empty($this->id_pdv_store)) && (!empty($this->codigo_pdv_store)) && (!empty($this->codigo_barras_pdv_store)) ){
                    return [
                        'category_product'          => 'required',
                        'id_pdv_store'              => Rule::unique('kits', 'id_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'codigo_pdv_store'          => Rule::unique('kits', 'codigo_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'codigo_barras_pdv_store'   => Rule::unique('kits', 'codigo_barras_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->id_pdv_store)){
                    return [
                        'category_product'          => 'required',
                        'id_pdv_store'              => Rule::unique('kits', 'id_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->codigo_pdv_store)){
                    return [
                        'category_product'          => 'required',
                        'codigo_pdv_store'          => Rule::unique('kits', 'codigo_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else if(!empty($this->codigo_barras_pdv_store)){
                    return [
                        'category_product'          => 'required',
                        'codigo_barras_pdv_store'   => Rule::unique('kits', 'codigo_barras_pdv_store')->where(function ($query) {
                            return $query->where('store', $this->store)->where('id', '<>', $this->idkit);
                        }),
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                } else {
                    return [
                        'store'                     => 'required',
                        'category_product'          => 'required',
                        'name'                      => 'required|min:4|max:240',
                        'amount'                    => 'required',
                        'unit_price'                => 'required|regex:/^\d+(\.\d{1,2})?$/',
                        'unit_promotion_price'      => 'regex:/^\d+(\.\d{1,2})?$/',
                        'description'               => 'required',
                    ];
                }

            }
        }
    }

    public function messages()
    {
        $msgRequired    = 'campo é obrigatório';
        $msgMin4        = 'campo deve ter pelo menos 4 caracteres';
        $msgMax         = 'campo máximo até 240 ';
        $msgPrice       = 'campo deve ser do tipo preço';
        $msgUniqueValue = 'Este código já tem cadastro';

        return [
            'store.required'                    => $msgRequired,
            'category_product.required'         => $msgRequired,
            'id_pdv_store.unique'               => $msgUniqueValue,
            'codigo_pdv_store.unique'           => $msgUniqueValue,
            'codigo_barras_pdv_store.unique'    => $msgUniqueValue,
            'name.required'                     => $msgRequired,
            'name.min'                          => $msgMin4,
            'name.max'                          => $msgMax,
            'amount.required'                   => $msgRequired,
            'unit_price.required'               => $msgRequired,
            'unit_price.regex'                  => $msgPrice,
            'unit_promotion_price.regex'        => $msgPrice,
            'description.required'              => $msgRequired,
        ];
    }
}
