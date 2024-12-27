<?php

namespace App\Interfaces\ProviderService;

interface ProviderServiceRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    public function findById($id);
    public function deleteByProvider($id);
    public function findAll();
    public function all();
}
