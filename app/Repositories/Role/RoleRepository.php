<?php

namespace App\Repositories\Role;

use Spatie\Permission\Models\Role;
use App\Interfaces\Role\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    protected $model;

    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $role = $this->model->findOrFail($id);
        $role->update($attributes);
        return $role;
    }

    public function delete($id)
    {
        $role = $this->model->findOrFail($id);
        $role->delete();
        return $role;
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findAll()
    {
        return $this->model->where('name', '!=', 'admin')->get();
    }

    public function all()
    {
        return $this->model->all();
    }
}
