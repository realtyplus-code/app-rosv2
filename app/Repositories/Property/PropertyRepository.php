<?php

namespace App\Repositories\Property;

use App\Models\Property\Property;
use App\Interfaces\Property\PropertyRepositoryInterface;

class PropertyRepository implements PropertyRepositoryInterface
{
    protected $model;

    public function __construct(Property $property)
    {
        $this->model = $property;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $property = $this->model->findOrFail($id);
        $property->update($attributes);
        return $property;
    }

    public function delete($id)
    {
        $property = $this->model->findOrFail($id);
        $property->delete();
        return $property;
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
