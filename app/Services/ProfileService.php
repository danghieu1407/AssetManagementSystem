<?php

namespace App\Services;

use App\Repositories\ProfileRepository;
use App\Services\BaseService;

class ProfileService extends BaseService
{
    public function __construct(ProfileRepository $ProfileRepository)
    {
         $this->ProfileRepository = $ProfileRepository;
    }


    public function getAll()
    {
        return $this->ProfileRepository->getAll();
    }

    public function changePassword($request)
    {
        return $this->ProfileRepository->kindOfChangePassword($request);
    }
}
