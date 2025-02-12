<?php

namespace App\Repositories\Contracts;

use App\Models\Contracts\PropertyHistory;
use App\Interfaces\Contract\PropertyHistoryRepositoryInterface;

class PropertyHistoryRepository implements PropertyHistoryRepositoryInterface
{
    protected $model;

    public function __construct(PropertyHistory $contract)
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
