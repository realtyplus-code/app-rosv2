<?php

namespace App\Services\Role;

use App\Interfaces\Role\RoleRepositoryInterface;

class RoleService
{
    protected $roleRepository;
    protected $fileService;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getRolsQuery()
    {
        return $this->roleRepository->findAll();
    }

}