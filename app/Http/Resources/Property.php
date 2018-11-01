<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Property extends JsonResource
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
            'id' => $this->id,
            'streetAddress' => $this->street_address,
            'city' => $this->city,
            'postCode' => $this->post_code,
            'country' => $this->country,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude
        ];
    }
}
