<?php

namespace App\Services\UserProperty;

use Illuminate\Support\Facades\Log;
use App\Interfaces\UserProperty\UserPropertyRepositoryInterface;

class UserPropertyService
{
    protected $userPropertyRepository;

    public function __construct(UserPropertyRepositoryInterface $userPropertyRepository)
    {
        $this->userPropertyRepository = $userPropertyRepository;
    }


    public function showProperty($id)
    {
        try {
            return $this->userPropertyRepository->findByUser($id);
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }
}
