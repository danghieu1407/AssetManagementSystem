<?php

namespace App\Repositories;

use App\Http\Resources\ReportResource;
use App\Models\Asset;
use App\Models\Category;
use App\Repositories\BaseRepository;
use http\Env\Request;
use Illuminate\Database\Eloquent\Builder;

class ReportRepository extends BaseRepository
{
    public function __construct()
    {
        $this->query = Category::query();
    }

    public function getAll($request)
    {
        $data = $this->query
            ->withCount(["assets AS count_available" => function (Builder $query) {
                $query->where('state', Asset::AVAILABLE_STATE);
            }, "assets AS count_not_available" => function (Builder $query) {
                $query->where('state', Asset::NOT_AVAILABLE);
            }, "assets AS count_assinged" => function (Builder $query) {
                $query->where('state', Asset::ASSIGNED_STATE);
            }, "assets AS count_waiting_for_recycling" => function (Builder $query) {
                $query->where('state', Asset::WAITING_FOR_RECYCLING);
            }, "assets AS count_recycled" => function (Builder $query) {
                $query->where('state', Asset::RECYCLED);
            }, "assets AS total" => function (Builder $query) {
                $query->whereIn('state', [
                    Asset::AVAILABLE_STATE,
                    Asset::NOT_AVAILABLE,
                    Asset::ASSIGNED_STATE,
                    Asset::WAITING_FOR_RECYCLING,
                    Asset::RECYCLED
                ]);
            }])
            ->sortByCategory($request)
            ->sortByTotal($request)
            ->sortByAvailable($request)
            ->sortByNotAvailable($request)
            ->sortByAssigned($request)
            ->sortByWaitingForRecycling($request)
            ->sortByRecycled($request);

        return ReportResource::collection($data->get());
    }
}
