<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class carResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
       'id'=>$this->id,
       'brandName' => $this->brandName,
       'modelName'=> $this->modelName,
       'manufactureYear' => $this->manufactureYear,
       'distance' => $this->distance,
       'condition' => $this->condition,
       'price' => $this->price,
       'dealershipId' => $this->dealershipId,
       'description' => $this->description,
       'dealership' => [
       'dealershipName' => $this->dealership->dealershipName,],

        ];
    }
}
