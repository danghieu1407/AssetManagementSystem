<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReturningRequest;
use App\Models\Assignment;
use App\Services\ManageReturningService;
use Illuminate\Http\Request;

class ManageReturningController extends Controller
{
    private ManageReturningService $manageReturningService;

    public function __construct(ManageReturningService $manageReturningService)
    {
        $this->manageReturningService = $manageReturningService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->manageReturningService->getAll($request);
    }
    public function store(Assignment $assignment)
    {
        return $this->manageReturningService->store($assignment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->manageReturningService->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) //UpdateReturningRequest
    {
        return $this->manageReturningService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->manageReturningService->delete($id);
    }
}
