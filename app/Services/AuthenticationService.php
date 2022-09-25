<?php

namespace App\Services;

use App\Services\BaseService;
use App\Models\User;
use App\Repositories\AuthenticationRepository;
use Illuminate\Http\Request;
use App\Repositories\ManageUserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationService extends BaseService
{
    public function __construct(
        ManageUserRepository $ManageUserRepository,
        AuthenticationRepository $AuthenticationRepository
    ) {
        $this->ManageUserRepository = $ManageUserRepository;
        $this->AuthenticationRepository = $AuthenticationRepository;
    }
    public function login($request)
    {
        return $this->AuthenticationRepository->login($request);
    }

    public function getAll()
    {
        return $this->manageUserRepository->getAll();
    }
    public function getUserInformation($request)
    {
        return $this->AuthenticationRepository->getUserInformation($request);
    }

    public function logout($request)
    {
        return $this->AuthenticationRepository->logout($request);
    }
}
