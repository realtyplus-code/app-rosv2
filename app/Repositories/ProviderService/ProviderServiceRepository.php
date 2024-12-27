<?php

namespace App\Repositories\ProviderService;

use App\Models\ProviderService\ProviderService;
use App\Interfaces\ProviderService\ProviderServiceRepositoryInterface;

class ProviderServiceRepository implements ProviderServiceRepositoryInterface
{
    protected $model;

    public function __construct(ProviderService $providerService)
    {
        $this->model = $providerService;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $providerService = $this->model->findOrFail($id);
        $providerService->update($attributes);
        return $providerService;
    }

    public function delete($id)
    {
        $providerService = $this->model->findOrFail($id);
        $providerService->delete();
        return $providerService;
    }

    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function deleteByProvider($id)
    {
        return $this->model->where('provider_id', $id)->delete();
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
