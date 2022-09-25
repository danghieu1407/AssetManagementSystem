<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\Returning;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ReturningService extends BaseService
{
    protected $returning;
    protected $assignment;
    public function __construct(
        Returning $returning,
        Assignment $assignment
    ) {
        $this->returning = $returning;
        $this->assignment = $assignment;
    }

    public function store(Assignment $assignment)
    {
        $user = request()->user();
        if ($assignment->staff_id != $user->id) {
            return response()->json([
                'error' => "You don't have permission to create returning request for this assignment"
            ], 400);
        } else {
            if ($assignment->state == 1) {
                $return = $this->returning->create([
                    'assignment_id' => $assignment->id,
                    'requested_by' => $user->id,
                    'state' => 0
                ]);
                $assignment->update([
                    'state' => 2 //waiting for returning
                ]);
                return response()->json([
                    'data' => $return,
                ], 201);
            }
        }
    }
}
