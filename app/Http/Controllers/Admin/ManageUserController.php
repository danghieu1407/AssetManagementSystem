<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Services\ManageUserService;
use Illuminate\Http\Request;
use App\Http\Requests\CreateUserRequest;

class ManageUserController extends Controller
{
    private ManageUserService $manageUserService;
    public function __construct(ManageUserService $manageUserService)
    {
        $this->ManageUserService = $manageUserService;
    }
    public function index()
    {
        return $this->ManageUserService->getAll();
    }
//    public function search($keyword)
//    {
//        return $this->ManageUserService->search($keyword);
//    }
    public function show($id)
    {
        return $this->ManageUserService->show($id);
    }

    /**
     * Edit the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request, int $id)
    {
        return $this->ManageUserService->edit($request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        return $this->ManageUserService->update($request, $id);
    }

    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        return $this->ManageUserService->store($input);
    }

    public function disable($id)
    {
        return $this->ManageUserService->disable($id);
    }
    public function canDisable($id)
    {
        return $this->ManageUserService->canDisable($id);
    }
    public function manageUser(Request $request)
    {
        return $this->ManageUserService->manageUser($request);
    }
}
