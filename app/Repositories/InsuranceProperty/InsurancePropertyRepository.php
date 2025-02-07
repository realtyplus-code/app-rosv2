<?php

namespace App\Repositories\InsuranceProperty;

use App\Models\InsuranceProperty\InsuranceProperty;
use App\Interfaces\InsuranceProperty\InsurancePropertyRepositoryInterface;

class InsurancePropertyRepository implements InsurancePropertyRepositoryInterface
{
    protected $model;

    public function __construct(InsuranceProperty $insuranceProperty)
    {
        $this->model = $insuranceProperty;
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function delete($id)
    {
        $insuranceProperty = $this->model->findOrFail($id);
        $insuranceProperty->delete();
        return $insuranceProperty;
    }

    public function deleteByProperty($id)
    {
        return $this->model->where([
            'property_id' => $id
        ])->delete();
    }

    public function deleteByInsurance($id)
    {
        return $this->model->where([
            'insurance_id' => $id
        ])->delete();
    }
}
