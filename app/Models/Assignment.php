<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    public const ACCEPT_STATE = 1;
    public const WAITING_STATE = 0;
    public const DECLINE_STATE = -1;

    protected $table = 'assignment';
    protected $fillable = [
        'asset_id',
        'staff_id',
        'note',
        'assigned_date',
        'assigned_by',
        'state'
    ];


    public const DECLINE = -1;
    public const WAITING_FOR_ACCEPTANCE = 0;
    public const ACCEPTED = 1;
    public const WAITING_FOR_RETURNING = 2;
    public const ALL = 3;
    public const COMPLETED = 4;

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function returning()
    {
        return $this->hasOne(Returning::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function scopeSearch($query, $request)
    {
        return $query
            ->when($request->has('search'), function ($query) use ($request) {
                $search = $request->query('search');
                $query
                    ->whereRelation("asset", "asset_code", "ilike", "%{$search}%")
                    ->orWhereRelation("asset", "name", "ilike", "%{$search}%")
                    ->orWhereRelation("staff", "username", "ilike", "%{$search}%");
            });
    }

    public function scopeFilterByStateManage($query, $request)
    {
        $filterByState = explode(',', $request->query('filterByStateManage'));
        if (in_array(3, $filterByState)) {
            return $query->whereIn("state", [
                self::DECLINE,
                self::WAITING_FOR_ACCEPTANCE,
                self::ACCEPTED
            ]);
        }
        return $query
            ->when($request->has('filterByStateManage'), function ($query) use ($filterByState) {
                $query->whereIn("state", $filterByState);
            });
    }

    public function scopeFilterByStateHome($query, $request)
    {
        $filterByState = explode(',', $request->query('filterByStateHome'));
        if (in_array(3, $filterByState)) {
            return $query->whereIn("state", [
                self::WAITING_FOR_ACCEPTANCE,
                self::ACCEPTED
            ]);
        }
        return $query
            ->when($request->has('filterByStateHome'), function ($query) use ($filterByState) {
                $query->whereIn("state", $filterByState);
            });
    }

    public function scopeFilterByDate($query, $request)
    {
        $filterByDate = $request->query('filterByDate');
        return $query
            ->when($request->has('filterByDate'), function ($query) use ($filterByDate) {
                $query->where("assigned_date", $filterByDate);
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
                    ->with('asset')
                    ->orderBy(
                        Asset::select('asset_code')->whereColumn('asset.id', 'assignment.asset_id'),
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
                    ->with('asset')
                    ->orderBy(
                        Asset::select('name')->whereColumn('asset.id', 'assignment.asset_id'),
                        $sortByAssetName
                    );
            });
    }

    public function scopeSortByAssignedTo($query, $request)
    {
        return $query
            ->when($request->has('sortByAssignedTo'), function ($query) use ($request) {
                $sortByAssignedTo = $request->query('sortByAssignedTo');
                $query
                    ->with('staff')
                    ->orderBy(
                        User::select('username')->whereColumn('user.id', 'assignment.staff_id'),
                        $sortByAssignedTo
                    );
            });
    }

    public function scopeSortByAssignedBy($query, $request)
    {
        return $query
            ->when($request->has('sortByAssignedBy'), function ($query) use ($request) {
                $sortByAssignedBy = $request->query('sortByAssignedBy');
                $query
                    ->with('assignedBy')
                    ->orderBy(
                        User::select('username')->whereColumn('user.id', 'assignment.assigned_by'),
                        $sortByAssignedBy
                    );
            });
    }

    public function scopeSortByAssignedDate($query, $request)
    {
        return $query
            ->when($request->has('sortByAssignedDate'), function ($query) use ($request) {
                $sortByAssignedDate = $request->query('sortByAssignedDate');
                $query->orderBy("assigned_date", $sortByAssignedDate);
            });
    }

    public function scopeSortByAssignedState($query, $request)
    {
        return $query
            ->when($request->has('sortByState'), function ($query) use ($request) {
                $sortByState = $request->query('sortByState');
                $query->orderBy("state", $sortByState);
            });
    }
    public function scopeLocation($query, $location)
    {
        $users = User::where('location', $location)->pluck('id');
        return $query->whereIn('staff_id', $users);
    }

    public function scopeSortByEditAssignment($querry, $request)
    {
        return $querry
            ->when($request->has('sortByEditAssignment'), function ($query) use ($request) {
                $query
                    ->orderBy("updated_at", 'desc');
            });
    }

    public function scopeSortByCreateAssignment($querry, $request)
    {
        return $querry
            ->when($request->has('sortByCreateAssignment'), function ($query) use ($request) {
                $query
                    ->orderBy("created_at", 'desc');
            });
    }
}
