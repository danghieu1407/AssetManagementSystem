<?php

namespace App\Repositories;

use App\Http\Resources\ReturningResource;
use App\Models\Asset;
use App\Models\Assignment;
use App\Models\Returning;
use App\Models\User;
use App\Repositories\BaseRepository;

class ManageReturningRepository extends BaseRepository
{
    public function __construct()
    {
        $this->query = Returning::query();
    }

    public function getAll($request, $sanctumUser)
    {
        $data = $this->query
            ->search($request)
            ->filterByState($request)
            ->filterByDate($request)
            ->sortByNo($request)
            ->sortByAssetCode($request)
            ->sortByAssetName($request)
            ->sortByRequestedBy($request)
            ->sortByAssignedDate($request)
            ->sortByAcceptedBy($request)
            ->sortByReturnedDate($request)
            ->sortByState($request)
            ->sortByEditReturning($request)
            ->location($sanctumUser->location);

        return ReturningResource::collection($data->paginate(config('app.limit')));
    }

    public function update($request, $id)
    {
        if ($request->state != 1) {
            return response()->json(['message' => 'You can not update this returning!'], 422);
        }

        $returning = Returning::findOrFail($id);
        $assignment = Assignment::findOrFail($returning->assignment_id);
        $asset = Asset::findOrFail($assignment->asset_id);
        $admin = User::find($returning->accepted_by);
        if (!$admin) {
            $user = User::find($returning->requested_by);
            $returning->update(['accepted_by' => $user->id ]);
        }
        // update state returning = completed
        $returning->update([
            'state' => Returning::COMPLETED,
            'returned_date' => \Carbon\Carbon::now()->format('Y-m-d')
        ]);

        // update asset state = available
        $asset->update(['state' => Asset::AVAILABLE_STATE]);

        // update assignment state = completed to remove from assignment list
        $assignment->update(['state' => Assignment::COMPLETED]);

        return response()->json([
            'message' => 'Returning updated successfully'
        ], 200);
    }

    public function delete($id)
    {

        $returning = Returning::findOrFail($id);
        $assignment = Assignment::findOrFail($returning->assignment_id);
        $assignment->update(['state' => Assignment::ACCEPTED]);
        $returning->delete();
        return response()->json([
            'message' => 'Returning delete successfully'
        ], 200);
    }

    public function getById($id)
    {
        $data = $this->query->where('id', $id);
        return new ReturningResource($data->first());
    }
}
