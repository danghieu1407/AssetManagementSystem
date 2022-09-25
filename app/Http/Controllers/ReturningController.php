<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Services\ReturningService;
use Illuminate\Http\Request;

class ReturningController extends Controller
{
    protected $returningService;

    public function __construct(ReturningService $returningService)
    {
        $this->returningService = $returningService;
    }
    public function store(Assignment $assignment)
    {
        return $this->returningService->store($assignment);
    }
}
