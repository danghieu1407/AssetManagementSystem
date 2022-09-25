<?php

namespace App\Repositories;

use App\Http\Resources\AssetResource;
use App\Http\Resources\EditAssetResource;
use App\Http\Resources\ReportResource;
use App\Http\Resources\UserResource;
use App\Models\Asset;
use App\Repositories\BaseRepository;
use App\Models\User;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\NoReturn;
use phpDocumentor\Reflection\Types\Integer;
use Illuminate\Database\Eloquent\Builder;

class ManageAssetRepository extends BaseRepository
{
    public function __construct()
    {
        $this->query = Asset::query();
    }

    public function getAll($request, $sanctumUser)
    {
        $data = $this->query
            ->where('location', $sanctumUser->location)
            ->search($request)
            ->filterByCategory($request)
            ->filterByState($request)
            ->sortByAssetCode($request)
            ->sortByCategory($request)
            ->sortByName($request)
            ->sortByState($request)
            ->sortByEditAsset($request)
            ->sortByCreateAsset($request);

        if (empty($request->all())) {
            $data->orderBy('name', 'asc');
        }

        if (!$request->has('no-paginate')) {
            return AssetResource::collection($data->paginate(config('app.limit')));
        } else {
            return AssetResource::collection($data->where('state', 1)->get());
        }
    }

    public function getById($id)
    {
        return new AssetResource(
            $this->query->where('id', $id)->firstOrFail()
        );
    }

    public function edit($request, $id)
    {
        $asset = $this->query->find($id);
        return response()->json(new EditAssetResource($asset), 200);
    }

    public function update($request, $id): \Illuminate\Http\JsonResponse
    {
        $asset = $this->query->find($id);
        $asset->update($request->all());
        return response()->json([
            'message' => 'Asset updated successfully'
        ], 200);
    }
}
