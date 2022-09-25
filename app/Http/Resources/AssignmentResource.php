<?php

namespace App\Http\Resources;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $stateName = $this->state;
        switch ($stateName) {
            case 0:
                $stateName = 'Waiting for acceptance';
                break;
            case -1:
                $stateName = 'Decline';
                break;
            case 1:
                $stateName = 'Accepted';
                break;
            case 2:
                $stateName = 'Waiting for returning';
                break;
        }
        $staff = User::find($this->staff_id);
        $admin = User::find($this->assigned_by);
        $asset = Asset::find($this->asset_id);
        if ($staff->location === $admin->location && $staff->location === $asset->location) {
            $location = $staff->location;
        } else {
            $location = 'not_match';
        }

        $assignmentArray = [
            'id' => $this->id,
            'assigned_by' => new UserResource(User::findOrFail($this->assigned_by)),
            'staff' => new UserResource(User::findOrFail($this->staff_id)),
            'assigned_date' => $this->assigned_date,
            'note' => $this->note,
            'state' => [
                'code' => $this->state,
                'name' => $stateName
            ],
            'location' => $location,
        ];

        if (request()->route()->uri() !== 'api/asset/{asset}') {
            $assignmentArray['asset'] = new AssetResource($this->asset);
        }

        return $assignmentArray;
    }
}
