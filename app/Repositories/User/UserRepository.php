<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Interfaces\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $user = $this->model->findOrFail($id);
        $user->update($attributes);
        return $user;
    }

    public function delete($id)
    {
        $user = $this->model->findOrFail($id);
        $user->delete();
        return $user;
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findAll()
    {
        return $this->model->all();
    }
}
