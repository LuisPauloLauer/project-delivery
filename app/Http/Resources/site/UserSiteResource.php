<?php

namespace App\Http\Resources\site;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => $this->id,
            'universitybuilding'    => $this->universitybuilding,
            'name'                  => $this->name,
            'slug'                  => $this->slug,
            'cpf'                   => $this->cpf,
            'birth'                 => $this->birth,
            'sex'                   => $this->sex,
            'fone'                  => $this->fone,
            'email'                 => $this->email,
            'path_image'            => $this->path_image,
        ];
    }
}
