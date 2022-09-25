<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Assignment;
use App\Models\Returning;
use App\Models\User;
use App\Repositories\ManageReturningRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ManageReturningService extends BaseService
{
    protected $manageReturningRepository;
    protected $returning;
    protected $assignment;
    public function __construct(
        ManageReturningRepository $manageReturningRepository,
        Returning $returning,
        Assignment $assignment
    ) {
        $this->manageReturningRepository = $manageReturningRepository;
        $this->returning = $returning;
        $this->assignment = $assignment;
    }

    public function getAll($request)
    {
        $sanctumUser = auth('sanctum')->user();
        return $this->manageReturningRepository->getAll($request, $sanctumUser);
    }
    public function store(Assignment $assignment)
    {
        $user = request()->user();
//        if (!$user->is_admin && $assignment->staff_id != $user->id) {
//            return response()->json([
//                'error' => "You don't have permission to create returning request for this assignment"
//            ], 400);
//        }
        if ($user->admin) {
            if ($assignment->state == 1) {
                $return = $this->returning->create([
                    'assignment_id' => $assignment->id,
                    'requested_by' => $user->id,
                    'state' => 0,
                ]);
                $assignment->update([
                    'state' => 2, //waiting for returning
                ]);
                return response()->json([
                    'data' => $return,
                ], 201);
            }
        }
    }
    public function update($request, $id)
    {
        //check request data
        $sanctumUser = auth('sanctum')->user();
        if (!$sanctumUser || !$sanctumUser->admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        //if returning is not existed
        $returning = Returning::find($id);

        if (!$returning) {
            return response()->json([
                'message' => 'Returning not found',
            ], 404);
        }

        //if accepted_by is not existed
        $user = User::query()->find($returning->requested_by);
        $admin = User::query()->find($returning->accepted_by);
        $assignment = Assignment::query()->find($returning->assignment_id);
        $asset = Asset::query()->find($assignment->asset_id);
        if (!$admin) {
            $admin = $user;
        }

        if (
            $this->checkPermission($request, $sanctumUser, $returning, $assignment, $asset, $admin, $user)
            !== null
        ) {
            return $this->checkPermission($request, $sanctumUser, $returning, $assignment, $asset, $admin, $user);
        } else {
            return $this->manageReturningRepository->update($request, $id);
        }
    }

    public function checkPermission($request, $sanctumUser, $returning, $assignment, $asset, $admin, $user)
    {
        //check location
        if (
            ($sanctumUser->location !== $user->location)
            || ($sanctumUser
                ->location !== $admin
                ->location)
            || ($sanctumUser
                ->location !== $asset
                ->location)
        ) {
            return response()->json(['message' =>
                'You cannot accept the returning having asset in other location!'], 401);
        }
        //check state of returning

        if ($returning->state != 0) {
            return response()->json(['message' => 'You cannot accept this returning!'], 422);
        }
        return null;
    }

    public function delete($id)
    {
        //check request data
        $sanctumUser = auth('sanctum')->user();
        if (!$sanctumUser || !$sanctumUser->admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $returning = Returning::find($id);

        //if returning is not existed
        if (!$returning) {
            return response()->json([
                'message' => 'Returning not found',
            ], 404);
        }
        $assignment = Assignment::query()->find($returning->assignment_id);
        $asset = Asset::query()->find($assignment->asset_id);
        $user = User::query()->find($returning->requested_by);
        $admin = User::query()->find($returning->accepted_by);
        //if admin is not existed
        if (!$admin) {
            $admin = $user;
        }

        //check location
        if (
            ($sanctumUser->location !== $user->location)
            || ($sanctumUser->location !== $admin->location)
            || ($sanctumUser->location !== $asset->location)
        ) {
            return response()->json(['message' =>
             'You cannot delete the returning having asset in other location!'], 401);
        }
        //check state of returning
        if ($returning->state == 1) {
            return response()->json(['message' => 'You cannot delete this completed returning!'], 422);
        }

        return $this->manageReturningRepository->delete($id);
    }

    public function getById($id)
    {
        //check admin
        $sanctumUser = auth('sanctum')->user();
        if (!$sanctumUser || !$sanctumUser->admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->manageReturningRepository->getById($id);
    }
}
