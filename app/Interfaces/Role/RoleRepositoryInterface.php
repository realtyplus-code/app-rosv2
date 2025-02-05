<?php

namespace App\Interfaces\Role;

interface RoleRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    public function findById($id);
    public function findByName($name);
    public function findAll();
    public function all();
}
