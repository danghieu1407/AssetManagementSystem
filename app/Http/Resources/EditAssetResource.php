<?php

namespace App\Http\Resources;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class EditAssetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $category = Category::query()->where('id', $this->category_id)->first();
        return [
            'name' => $this->name,
            'category' => $category->name,
            'specification' => $this->specification,
            'installed_date' => $this->installed_date,
            'state' => $this->state,
            'id' => $this->id
        ];
    }
}
