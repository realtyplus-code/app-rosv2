<?php

namespace App\Repositories\IncidentAction;

use App\Models\IncidentAction\IncidentAction;
use App\Interfaces\IncidentAction\IncidentActionRepositoryInterface;

class IncidentActionRepository implements IncidentActionRepositoryInterface
{
    protected $model;

    public function __construct(IncidentAction $incidentAction)
    {
        $this->model = $incidentAction;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $incidentAction = $this->model->findOrFail($id);
        $incidentAction->update($attributes);
        return $incidentAction;
    }

    public function delete($id)
    {
        $incidentAction = $this->model->findOrFail($id);
        $incidentAction->delete();
        return $incidentAction;
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
