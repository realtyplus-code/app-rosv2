<?php

namespace App\Repositories\Insurance;

use App\Models\Insurance\Insurance;
use App\Interfaces\Insurance\InsuranceRepositoryInterface;

class InsuranceRepository implements InsuranceRepositoryInterface
{
    protected $model;

    public function __construct(Insurance $insurance)
    {
        $this->model = $insurance;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $insurance = $this->model->findOrFail($id);
        $insurance->update($attributes);
        return $insurance;
    }

    public function delete($id)
    {
        $insurance = $this->model->findOrFail($id);
        $insurance->delete();
        return $insurance;
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findAll()
    {
        return $this->model->all();
    }

    public function all()
    {
        return $this->model->all();
    }
}
