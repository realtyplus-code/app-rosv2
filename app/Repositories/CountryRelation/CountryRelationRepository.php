<?php

namespace App\Repositories\CountryRelation;

use App\Models\Configuration\CountryRelation;
use App\Interfaces\CountryRelation\CountryRelationRepositoryInterface;

class CountryRelationRepository implements CountryRelationRepositoryInterface
{
    protected $model;

    public function __construct(CountryRelation $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $model = $this->model->findOrFail($id);
        $model->update($attributes);
        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        $model->delete();
        return $model;
    }

    public function deleteByCountryId($id)
    {
        return $this->model->where('country_id', $id)->delete();
    }
}
