<?php

namespace App\Http\Resources\site;

use Illuminate\Http\Resources\Json\JsonResource;

class UniversityBuildingsResource extends JsonResource
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
            'id'            => $this->id,
            'company_name'  => $this->company_name,
            'building_name' => $this->building_name,
            'description'   => $this->description,
        ];
    }
}
