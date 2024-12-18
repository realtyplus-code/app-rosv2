<?php

namespace App\Repositories\UserProperty;

use App\Models\UserProperty\UserProperty;
use App\Interfaces\UserProperty\UserPropertyRepositoryInterface;

class UserPropertyRepository implements UserPropertyRepositoryInterface
{
    protected $model;

    public function __construct(UserProperty $userProperty)
    {
        $this->model = $userProperty;
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

    public function deleteByProperty($id)
    {
        return $this->model->where([
            'property_id' => $id
        ])->delete();
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findAll()
    {
        return $this->model->all();
    }

    public function all()
    {
        return $this->model->all();
    }
}
