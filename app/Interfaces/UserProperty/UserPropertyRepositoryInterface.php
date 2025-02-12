<?php

namespace App\Interfaces\UserProperty;

interface UserPropertyRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    public function deleteByProperty($id);
    public function findById($id);
    public function findByUser($id);
    public function findByProperty($id);
    public function findAll();
    public function all();
}
