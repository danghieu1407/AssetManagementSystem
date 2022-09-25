<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'first_name' => ucfirst($this->first_name),
            'last_name' => ucwords($this->last_name),
            'date_of_birth' => date_format($this->date_of_birth, "Y-m-d"),
            'joined_date' => date_format($this->joined_date, "Y-m-d"),
            'gender' => $this->gender,
            'type' => $this->admin,
            'id' => $this->id,
        ];
    }
}
