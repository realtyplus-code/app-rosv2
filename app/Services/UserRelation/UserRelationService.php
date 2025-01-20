<?php

namespace App\Services\UserRelation;

use App\Models\UserRelation\UserRelation;
use App\Repositories\UserRelation\UserRelationRepository;

class UserRelationService
{
    protected $userRelationRepository;

    public function __construct(UserRelationRepository $userRelationRepository)
    {
        $this->userRelationRepository = $userRelationRepository;
    }

    public function create(array $data)
    {
        return $this->userRelationRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->userRelationRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->userRelationRepository->delete($id);
    }
}
