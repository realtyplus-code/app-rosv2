<?php

namespace App\Repositories\Contracts;

use App\Models\Contracts\UserPropertyHistory;
use App\Interfaces\Contract\UserPropertyHistoryRepositoryInterface;

class UserPropertyHistoryRepository implements UserPropertyHistoryRepositoryInterface
{
    protected $model;

    public function __construct(UserPropertyHistory $contract)
    {
        $this->model = $contract;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $contract = $this->model->findOrFail($id);
        $contract->update($attributes);
        return $contract;
    }

    public function delete($id)
    {
        $contract = $this->model->findOrFail($id);
        $contract->delete();
        return $contract;
    }
}
