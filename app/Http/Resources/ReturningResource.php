<?php

namespace App\Http\Resources;

use App\Models\Returning;
use App\Models\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ReturningResource extends JsonResource
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
            case Returning::WAITING_FOR_RETURNING:
                $stateName = 'Waiting for returning';
                break;
            case Returning::COMPLETED:
                $stateName = 'Completed';
                break;
        }

        return [
            'id' => $this->id,
            'assignment' => new AssignmentResource($this->assignment),
            'accepted_by' => $this->accepted_by ? new UserResource(User::findOrFail($this->accepted_by)) : null,
            'requested_by' => new UserResource(User::findOrFail($this->requested_by)),
            'return_date' => $this->returned_date,
            'returning_state' => $stateName,
            'updated_at' => $this->updated_at,
        ];
    }
}
