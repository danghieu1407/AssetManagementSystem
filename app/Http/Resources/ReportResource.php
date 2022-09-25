<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'category_name' => $this->name,
            // 'asset' =>  AssetResource::collection($this->assets),
            // 'total2' =>  AssetResource::collection($this->assets)->count(),
            'total' => $this->total,
            'count_available' => $this->count_available,
            'count_not_available' => $this->count_not_available,
            'count_assinged' => $this->count_assinged,
            'count_waiting_for_recycling' => $this->count_waiting_for_recycling,
            'count_recycled' => $this->count_recycled
        ];
    }
}
