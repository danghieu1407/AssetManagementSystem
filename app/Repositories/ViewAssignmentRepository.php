<?php

namespace App\Repositories;

use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class ViewAssignmentRepository extends BaseRepository
{
    public function __construct()
    {
        $this->query = Assignment::query();
    }

    public function getAll($request, $santumUser)
    {

        $data = $this->query
            ->where('staff_id', $santumUser->id)
            ->where('assigned_date', '<=', date("Y-m-d"))
            ->search($request)
            ->filterByStateHome($request)
            ->filterByDate($request)
            ->sortByNo($request)
            ->sortByAssetCode($request)
            ->sortByAssetName($request)
            ->sortByAssignedTo($request)
            ->sortByAssignedBy($request)
            ->sortByAssignedDate($request)
            ->sortByAssignedState($request);

        return AssignmentResource::collection($data->get());
    }

    public function getById($id)
    {
        $data = $this->query->where('id', $id);

        return new AssignmentResource($data->first());
    }
}
