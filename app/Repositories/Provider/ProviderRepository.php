<?php

namespace App\Repositories\Provider;

use App\Models\Provider\Provider;
use App\Interfaces\Provider\ProviderRepositoryInterface;

class ProviderRepository implements ProviderRepositoryInterface
{
    protected $model;

    public function __construct(Provider $provider)
    {
        $this->model = $provider;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $provider = $this->model->findOrFail($id);
        $provider->update($attributes);
        return $provider;
    }

    public function delete($id)
    {
        $provider = $this->model->findOrFail($id);
        $provider->delete();
        return $provider;
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
