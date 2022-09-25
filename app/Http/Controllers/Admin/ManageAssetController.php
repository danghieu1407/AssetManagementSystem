<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Services\ManageAssetService;
use App\Services\ManageUserService;
use Illuminate\Http\Request;

class ManageAssetController extends Controller
{
    private ManageAssetService $manageAssetService;
    public function __construct(ManageAssetService $manageAssetService)
    {
        $this->manageAssetService = $manageAssetService;
    }
    public function index(Request $request)
    {
        return $this->manageAssetService->getAll($request);
    }
//    public function search($keyword)
//    {
//        return $this->manageAssetService->search($keyword);
//    }
    public function store(CreateAssetRequest $request)
    {
        $input = $request->all();
        return $this->manageAssetService->store($input);
    }
    public function show($id)
    {
        return $this->manageAssetService->getById($id);
    }
    public function edit(Request $request, int $id)
    {
        return $this->manageAssetService->edit($request, $id);
    }
    public function update(UpdateAssetRequest $request, int $id)
    {
        return $this->manageAssetService->update($request, $id);
    }
    public function destroy($id)
    {
        return $this->manageAssetService->disable($id);
    }
    public function canDestroy($id)
    {
        return $this->manageAssetService->assignmentValid($id);
    }
}
