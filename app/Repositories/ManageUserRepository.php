<?php

namespace App\Repositories;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\NewUserResource;
use App\Http\Resources\UserResource;
use App\Repositories\BaseRepository;
use App\Models\User;
use App\Rules\JoinedDateWeekend;
use App\Rules\LatinName;
use App\Rules\Over18;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;
use phpDocumentor\Reflection\Types\Integer;

class ManageUserRepository extends BaseRepository
{
    // public int $default_paginate = 5;

    public function __construct()
    {
        $this->query = User::query();
    }
    public function getAll()
    {
        return $this->query->get();
    }
    public function disable($id)
    {
        $sanctumUser = auth('sanctum')->user();
        if (!$sanctumUser || !$sanctumUser->admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } else {
            $user = $this->query->findOrFail($id);
            if ($user) {
                $user->update([
                    'state' => $user->state = -1
                ]);
                return response()->json([
                    'message' => 'update state user disable',
                ], 200);
            }
        }
    }

    public function manageUser($request)
    {
        //check admin
        $sanctumUser = auth('sanctum')->user();
        if (!$sanctumUser || !$sanctumUser->admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $this->query
        ->where('location', $sanctumUser->location)
        ->where('state', '!=', -1)
        ->search($request)
        ->filter($request)
        ->sortByFullName($request)
        ->sortByStaffCode($request)
        ->sortByJoinedDate($request)
        ->sortByType($request)
        ->sortByEditUser($request)
        ->sortByCreateUser($request)
        ->orderBy('first_name')
        ->orderBy('staff_code')
        ->orderBy('joined_date')
        ->orderBy('admin');

        if (!$request->has('no-paginate')) {
            return UserResource::collection($data->where('id', '!=', $sanctumUser->id)->paginate(config('app.limit')));
        } else {
            return UserResource::collection($data->get());
        }
    }

    public function edit($request, $id)
    {
        //check admin
        $sanctumUser = auth('sanctum')->user();
        if (!$sanctumUser || !$sanctumUser->admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        //check user
        $user = $this->query->find($id);
        //check user exist
        if (!$user) {
            return response()->json([
                'message' => 'user is not exist'
            ], 404);
        }
        //check user location
        if ($user->location != $sanctumUser->location) {
            return response()->json([
                'message' => 'you do not have permission to edit user in other location'
            ], 401);
        } else {
            return response()->json(new NewUserResource($user), 200);
        }
    }

    public function update($request, $id): \Illuminate\Http\JsonResponse
    {
        //check admin
        $sanctumUser = auth('sanctum')->user();
        if (!$sanctumUser || !$sanctumUser->admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = $this->query->find($id);
        //check user exist
        if (!$user) {
            return response()->json([
                'message' => 'User is not exist'
            ], 404);
        }
        //check user location
        if ($user->location != $sanctumUser->location) {
            return response()->json([
                'message' => 'You do not have permission to edit user in other location'
            ], 401);
        }
        //user must be >18 years old
        if ($this->diffYear($request->date_of_birth, date('Y-m-d H:i:s')) < 18) {
            return response()->json([
                'message' => 'User age must be larger than 18'
            ], 422);
        }
        //date of birth must be before join date
        if ($this->diffYear($request->date_of_birth, $request->joined_date) < 0) {
            return response()->json([
                'message' => 'Date of birth must be before join date'
            ], 422);
        }
        //join date must be workday
        if ($this->isWeekend($request->joined_date)) {
            return response()->json([
                'message' => 'Join date must be a workday'
            ], 422);
        }
        //success response
        $user->update($request->all());
        return response()->json([
            'message' => 'User has been updated successfully'
        ], 200);
    }
    public function diffYear($date1, $date2): float
    {
        $diff = (strtotime($date2) - strtotime($date1));
        return floor($diff / (365 * 60 * 60 * 24));
    }
    public function isWeekend($date): bool
    {
        $weekDay = date('w', strtotime($date));
        return ($weekDay == 0 || $weekDay == 6);
    }
    public function show($id)
    {
        $user = User::query()->where("id", $id)->first();
        return new UserResource($user);
    }
}
