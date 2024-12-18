<?php

namespace App\Interfaces\User;

interface UserRepositoryInterface
{
    public function all();
    public function findById($id);
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
}