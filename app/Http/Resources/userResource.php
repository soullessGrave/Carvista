<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class userResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'name' => $this->name,
        'email' => $this->email,
        'username'  => $this->username,
        'phoneNumber' => $this->phoneNumber,
        'city' => $this->city,
        'locationUrl' => $this->locationUrl,
        ];
    }
}
