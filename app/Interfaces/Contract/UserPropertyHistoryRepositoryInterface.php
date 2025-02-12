<?php

namespace App\Interfaces\Contract;

interface UserPropertyHistoryRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
}
