<?php

namespace App\Interfaces\Contract;

interface PropertyHistoryRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    public function findBy(array $conditions);
}
