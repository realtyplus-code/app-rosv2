<?php

namespace App\Interfaces\CountryRelation;

interface CountryRelationRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    public function deleteByCountryId($id);
}
