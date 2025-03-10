<?php

namespace App\Interfaces\InsuranceProperty;

interface InsurancePropertyRepositoryInterface
{
    public function create(array $attributes);
    public function delete($id);
    public function deleteByProperty($id);
    public function deleteByInsurance($id);
}
