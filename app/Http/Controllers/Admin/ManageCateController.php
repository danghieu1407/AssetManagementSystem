<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Services\ManageCateService;
use Illuminate\Http\Request;

class ManageCateController extends Controller
{
    private ManageCateService $manageCateService;
    public function __construct(ManageCateService $manageCateService)
    {
        $this->ManageCateService = $manageCateService;
    }
    public function index()
    {
        return $this->ManageCateService->getAll();
    }
    public function create()
    {
        //
    }
    public function store(StoreCategoryRequest $request)
    {
        $input = $request->all();
        return $this->ManageCateService->store($input);
    }
    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id)
    {
        //
    }
}
