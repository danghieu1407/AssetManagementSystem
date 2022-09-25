<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $table = 'user';
    protected $id_user = 'SD';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staff_code',
        'first_name',
        'last_name',
        'date_of_birth',
        'password',
        'state',
        'username',
        'base_username',
        'location',
        'gender',
        'joined_date',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'datetime',
        'joined_date' => 'datetime',
    ];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function scopeSearch($query, $request)
    {
        return $query
        ->when($request->has('search'), function ($query) use ($request) {
            $search = $request->query('search');
            $query
                ->where("first_name", "ILIKE", "%{$search}%")
                ->orWhere("last_name", "ILike", "%{$search}%")
                ->orWhere("staff_code", "ILIKE", "%{$search}%")
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) ILIKE '%{$search}%'");
        });
    }

    public function scopeFilter($querry, $request)
    {
        return $querry
            ->when($request->has('filter'), function ($query) use ($request) {
                $filter = $request->query('filter');
                $query
                    ->where("admin", "=", $filter);
            });
    }
    public function scopeSortByFullname($query, $request)
    {
        return $query
            ->when($request->has('sortByFullName'), function ($query) use ($request) {
                $sortByFullName = $request->query('sortByFullName');
                $query
                    ->orderBy("first_name", $sortByFullName);
            });
    }

    public function scopeSortByStaffCode($querry, $request)
    {
        return $querry
            ->when($request->has('sortByStaffCode'), function ($query) use ($request) {
                $sortByStaffCode = $request->query('sortByStaffCode');
                $query
                    ->orderBy("staff_code", $sortByStaffCode);
            });
    }

    public function scopeSortByJoinedDate($querry, $request)
    {
        return $querry
            ->when($request->has('sortByJoinedDate'), function ($query) use ($request) {
                $sortByJoinedDate = $request->query('sortByJoinedDate');
                $query
                    ->orderBy("joined_date", $sortByJoinedDate);
            });
    }

    public function scopeSortByType($querry, $request)
    {
        return $querry
            ->when($request->has('sortByType'), function ($query) use ($request) {
                $sortByType = $request->query('sortByType');
                $query
                    ->orderBy("admin", $sortByType);
            });
    }
    public function scopeSortByEditUser($querry, $request)
    {
        return $querry
            ->when($request->has('sortByEditUser'), function ($query) use ($request) {
                $sortByEditUser = $request->query('sortByEditUser');
                $query
                    ->orderBy("updated_at", 'desc');
            });
    }

    public function scopeSortByCreateUser($querry, $request)
    {
        return $querry
            ->when($request->has('sortByCreateUser'), function ($query) use ($request) {
                $sortByCreateUser = $request->query('sortByCreateUser');
                $query
                    ->orderBy("created_at", 'desc');
            });
    }
}
