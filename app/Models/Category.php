<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'category';
    public $primaryKey = 'id';
    protected $casts = ['id' => 'string'];
    public $incrementing = false;
    protected $fillable = ['id', 'name'];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function scopeSortByCategory($query, $request)
    {
        return $query
            ->when($request->has('sortByCategory'), function ($query) use ($request) {
                $query->orderBy('name', $request->query('sortByCategory'));
            });
    }

    public function scopeSortByTotal($query, $request)
    {
        return $query
            ->when($request->has('sortByTotal'), function ($query) use ($request) {
                $query->orderBy('total', $request->query('sortByTotal'));
            });
    }

    public function scopeSortByAvailable($query, $request)
    {
        return $query
            ->when($request->has('sortByAvailable'), function ($query) use ($request) {
                $query->orderBy('count_available', $request->query('sortByAvailable'));
            });
    }

    public function scopeSortByNotAvailable($query, $request)
    {
        return $query
            ->when($request->has('sortByNotAvailable'), function ($query) use ($request) {
                $query->orderBy('count_not_available', $request->query('sortByNotAvailable'));
            });
    }

    public function scopeSortByAssigned($query, $request)
    {
        return $query
            ->when($request->has('sortByAssigned'), function ($query) use ($request) {
                $query->orderBy('count_assinged', $request->query('sortByAssigned'));
            });
    }

    public function scopeSortByWaitingForRecycling($query, $request)
    {
        return $query
            ->when($request->has('sortByWaitingForRecycling'), function ($query) use ($request) {
                $query->orderBy('count_waiting_for_recycling', $request->query('sortByWaitingForRecycling'));
            });
    }

    public function scopeSortByRecycled($query, $request)
    {
        return $query
            ->when($request->has('sortByRecycled'), function ($query) use ($request) {
                $query->orderBy('count_recycled', $request->query('sortByRecycled'));
            });
    }
}
