<?php

namespace App\Repositories\IncidentProvider;

use App\Models\IncidentProvider\IncidentProvider;
use App\Interfaces\IncidentProvider\IncidentProviderRepositoryInterface;

class IncidentProviderRepository implements IncidentProviderRepositoryInterface
{
    protected $model;

    public function __construct(IncidentProvider $incidentProvider)
    {
        $this->model = $incidentProvider;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $incidentProvider = $this->model->findOrFail($id);
        $incidentProvider->update($attributes);
        return $incidentProvider;
    }

    public function delete($id)
    {
        $incidentProvider = $this->model->findOrFail($id);
        $incidentProvider->delete();
        return $incidentProvider;
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

    public function deleteByIncident($id)
    {
        return $this->model->where('incident_id', $id)->delete();
    }
}
