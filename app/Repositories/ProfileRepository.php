<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileRepository extends BaseRepository
{
    public function __construct()
    {
        $this->query = User::query();
    }

    public function changePassword($request, $user)
    {
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validations fails',
                'error' => $validator->errors()
            ], 422);
        }

        if (Hash::check($request->oldPassword, $user->password)) {
            $user->update([
                'password' => Hash::make($request->newPassword)
            ]);

            return response()->json([
                'message' => 'Your password has been changed successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Password is incorrect'
            ], 404);
        }
    }

    public function firstChangePassword($request, $user)
    {
        $validator = Validator::make($request->all(), [
            'newPassword' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validations fails',
                'error' => $validator->errors()
            ], 422);
        }

        $user->update([
            'password' => Hash::make($request->newPassword),
            'state' => '1'
        ]);

        return response()->json([
            'message' => 'Your password has been changed successfully'
        ], 200);
    }

    public function kindOfChangePassword($request)
    {
        $bearerArr = explode(" ", $request->header('Authorization'));
        $token = explode("|", $bearerArr[1]);
        $bearer = DB::table('personal_access_tokens')->where('id', $token[0])->first();
        $user = $this->query->where('id', $bearer->tokenable_id)->first();

        $state = $user->state;
        switch ($state) {
            case ('0'):
                return $this->firstChangePassword($request, $user);
            case ('1'):
                return $this->changePassword($request, $user);
        }
    }
}
