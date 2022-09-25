<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAssignmentRequest;
use App\Http\Requests\AssignmentResponeRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Services\ManageAssignmentService;
use Illuminate\Http\Request;

class ManageAssignmentController extends Controller
{
    private ManageAssignmentService $manageAssignmentService;

    public function __construct(ManageAssignmentService $manageAssignmentService)
    {
        $this->manageAssignmentService = $manageAssignmentService;
    }

        //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        return $this->manageAssignmentService->getAll($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAssignmentRequest $request)
    {
        $input = $request->all();
        return $this->manageAssignmentService->store($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->manageAssignmentService->getById($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        return $this->manageAssignmentService->edit($request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAssignmentRequest $request, $id)
    {
        return $this->manageAssignmentService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return $this->manageAssignmentService->destroy($request, $id);
    }

    public function canDelete(Request $request, $id)
    {
        return $this->manageAssignmentService->canDelete($request, $id);
    }

    public function response(AssignmentResponeRequest $request, $id)
    {
        return $this->manageAssignmentService->response($request, $id);
    }
}
