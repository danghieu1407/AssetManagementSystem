<?php

namespace App\Services;

use App\Repositories\ViewAssignmentRepository;
use App\Services\BaseService;

class ViewAssignmentService extends BaseService
{
    private ViewAssignmentRepository $viewAssignmentRepository;
    public function __construct(ViewAssignmentRepository $viewAssignmentRepository)
    {
        $this->viewAssignmentRepository = $viewAssignmentRepository;
    }

    public function getAll($request)
    {
        //check user
        $sanctumUser = auth('sanctum')->user();
        if (!$sanctumUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->viewAssignmentRepository->getAll($request, $sanctumUser);
    }

    public function getById($id)
    {
        //check user
        $sanctumUser = auth('sanctum')->user();
        if (!$sanctumUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->viewAssignmentRepository->getById($id);
    }
}
