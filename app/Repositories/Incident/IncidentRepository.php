<?php

namespace App\Repositories\Incident;

use App\Models\Incident\Incident;
use App\Interfaces\Incident\IncidentRepositoryInterface;

class IncidentRepository implements IncidentRepositoryInterface
{
    protected $model;

    public function __construct(Incident $incident)
    {
        $this->model = $incident;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $incident = $this->model->findOrFail($id);
        $incident->update($attributes);
        return $incident;
    }

    public function delete($id)
    {
        $incident = $this->model->findOrFail($id);
        $incident->delete();
        return $incident;
    }

    public function findById($id)
    {
        return $this->model->with([
            'property',
            'reportedBy',
            'assignedResponsible',
            'status',
            'incidentType',
            'priority',
            'payer',
        ])->findOrFail($id);
    }

    public function findAll()
    {
        return $this->model->with([
            'property',
            'reportedBy',
            'assignedResponsible',
            'status',
            'incidentType',
            'priority',
            'payer',
        ])->get();
    }

    public function all()
    {
        return $this->model->all();
    }
}
