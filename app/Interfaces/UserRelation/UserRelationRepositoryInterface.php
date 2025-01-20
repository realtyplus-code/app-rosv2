<?php

namespace App\Interfaces\UserRelation;

interface UserRelationRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    public function deleteByManagement($id);
}