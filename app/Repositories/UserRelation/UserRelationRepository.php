<?php

namespace App\Repositories\UserRelation;

use App\Models\UserRelation\UserRelation;
use App\Interfaces\UserRelation\UserRelationRepositoryInterface;

class UserRelationRepository implements UserRelationRepositoryInterface
{
    protected $model;

    public function __construct(UserRelation $model)
    {
        $this->model = $model;
    }

   public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $userProperty = $this->model->findOrFail($id);
        $userProperty->update($attributes);
        return $userProperty;
    }

    public function delete($id)
    {
        $userProperty = $this->model->findOrFail($id);
        $userProperty->delete();
        return $userProperty;
    }

    public function deleteByManagement($id)
    {
        return $this->model->where('user_id_related', $id)->delete();
    }
}
