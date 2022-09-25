<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returning extends Model
{
    use HasFactory;

    protected $table = 'returning';

    protected $fillable = [
        'assignment_id',
        'requested_by',
        'accepted_by',
        'returned_date',
        'state',
    ];

    public const WAITING_FOR_RETURNING = 0;
    public const COMPLETED = 1;
    public const ALL_STATE = 3;

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function acceptedBy()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function asset()
    {
        return $this->belongsTo(Assignment::class)->asset();
    }

    public function scopeSearch($query, $request)
    {
        return $query
            ->when($request->has('search'), function ($query) use ($request) {
                $search = $request->query('search');
                $query
                    ->whereRelation("assignment.asset", "asset_code", "ilike", "%{$search}%")
                    ->orWhereRelation("assignment.asset", "name", "ilike", "%{$search}%")
                    ->orWhereRelation("requestedBy", "username", "ilike", "%{$search}%");
            });
    }

    public function scopeFilterByState($query, $request)
    {
        $filterByState = explode(',', $request->query('filterByState'));
        if (in_array(self::ALL_STATE, $filterByState)) {
            return $query;
        }
        return $query
            ->when($request->has('filterByState'), function ($query) use ($filterByState) {
                $query->whereIn("state", $filterByState);
            });
    }

    public function scopeFilterByDate($query, $request)
    {
        $filterByDate = $request->query('filterByDate');
        return $query
            ->when($request->has('filterByDate'), function ($query) use ($filterByDate) {
                $query->where("returned_date", $filterByDate);
            });
    }

    public function scopeSortByNo($query, $request)
    {
        return $query
            ->when($request->has('sortByNo'), function ($query) use ($request) {
                $sortByNo = $request->query('sortByNo');
                $query
                    ->orderBy("id", $sortByNo);
            });
    }

    public function scopeSortByAssetCode($query, $request)
    {
        return $query
            ->when($request->has('sortByAssetCode'), function ($query) use ($request) {
                $sortByAssetCode = $request->query('sortByAssetCode');
                $query
                    ->with('assignment.asset')
                    ->orderBy(
                        Asset::select('asset_code')
                            ->join('assignment', 'returning.assignment_id', 'assignment.id')
                            ->whereColumn('assignment.asset_id', 'asset.id'),
                        $sortByAssetCode
                    );
            });
    }

    public function scopeSortByAssetName($query, $request)
    {
        return $query
            ->when($request->has('sortByAssetName'), function ($query) use ($request) {
                $sortByAssetName = $request->query('sortByAssetName');
                $query
                    ->with('assignment.asset')
                    ->orderBy(
                        Asset::select('asset.name')
                            ->join('assignment', 'returning.assignment_id', 'assignment.id')
                            ->whereColumn('assignment.asset_id', 'asset.id'),
                        $sortByAssetName
                    );
            });
    }

    public function scopeSortByRequestedBy($query, $request)
    {
        return $query
            ->when($request->has('sortByRequestedBy'), function ($query) use ($request) {
                $sortByRequestedBy = $request->query('sortByRequestedBy');
                $query
                    ->with('requestedBy')
                    ->orderBy(
                        User::select('username')->whereColumn('user.id', 'returning.requested_by'),
                        $sortByRequestedBy
                    );
            });
    }

    public function scopeSortByAssignedDate($query, $request)
    {
        return $query
            ->when($request->has('sortByAssignedDate'), function ($query) use ($request) {
                $sortByAssignedDate = $request->query('sortByAssignedDate');
                $query->with('assignment')
                    ->orderBy(
                        Assignment::select('assigned_date')->whereColumn('assignment.id', 'returning.assignment_id'),
                        $sortByAssignedDate
                    );
            });
    }

    public function scopeSortByAcceptedBy($query, $request)
    {
        return $query
            ->when($request->has('sortByAcceptedBy'), function ($query) use ($request) {
                $sortByAcceptedBy = $request->query('sortByAcceptedBy');
                $query
                    ->with('acceptedBy')
                    ->orderBy(
                        User::select('username')->whereColumn('user.id', 'returning.accepted_by'),
                        $sortByAcceptedBy
                    );
            });
    }

    public function scopeSortByReturnedDate($query, $request)
    {
        return $query
            ->when($request->has('sortByReturnedDate'), function ($query) use ($request) {
                $sortByReturnedDate = $request->query('sortByReturnedDate');
                $query->orderBy("returned_date", $sortByReturnedDate);
            });
    }

    public function scopeSortByState($query, $request)
    {
        return $query
            ->when($request->has('sortByState'), function ($query) use ($request) {
                $sortByState = $request->query('sortByState');
                $query->orderBy("state", $sortByState);
            });
    }

    public function scopeSortByEditReturning($query, $request)
    {
        return $query
            ->when($request->has('sortByEditReturning'), function ($query) use ($request) {
                $sortByEditReturning = $request->query('sortByEditReturning');
                $query->orderBy("updated_at", 'desc');
            });
    }

    public function scopeLocation($query, $location)
    {
        $users = User::where('location', $location)->pluck('id');
        return $query->whereIn('requested_by', $users);
    }
}
