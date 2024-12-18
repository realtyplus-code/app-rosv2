<?php

namespace App\Interfaces\Property;

interface PropertyRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    public function findById($id);
    public function findAll();
    public function all();
}
