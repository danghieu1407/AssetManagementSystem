<?php

namespace App\Http\Resources;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class EditAssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $asset = Asset::query()->where('id', $this->asset_id)->first();
        $user = User::query()->where('id', $this->staff_id)->first();
        $admin = User::query()->where('id', $this->assigned_by)->first();
        return [
            'id' => $this->id,
            'asset' => [
                'id' => $this->asset_id,
                'name' => $asset->name,
                'asset_code' => $asset->asset_code
            ],
            'staff' => [
                'id' => $this->staff_id,
                'name' => ucfirst($user->first_name) . ' ' . ucwords($user->last_name),
                'staff_code' => $user->staff_code,
                'username' => $user->username
            ],
            'assigned_by' => [
                'id' => $this->assigned_by,
                'name' => ucfirst($admin->first_name) . ' ' . ucwords($admin->last_name),
                'staff_code' => $admin->username,
                'username' => $admin->staff_code
            ],
            'assigned_date' => $this->assigned_date,
            'note' => $this->note,
        ];
    }
}
