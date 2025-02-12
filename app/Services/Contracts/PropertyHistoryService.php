<?php

namespace App\Services\Contracts;

use Carbon\Carbon;
use App\Models\Property;
use App\Models\PropertyHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Property\PropertyRepositoryInterface;
use App\Interfaces\Contract\PropertyHistoryRepositoryInterface;
use App\Interfaces\UserProperty\UserPropertyRepositoryInterface;
use App\Interfaces\Contract\UserPropertyHistoryRepositoryInterface;

class PropertyHistoryService
{
    protected $propertyRepository;
    protected $propertyHistoryRepository;
    protected $userPropertyHistoryRepository;
    protected $userPropertyRepository;

    public function __construct(
        PropertyRepositoryInterface $propertyRepository,
        PropertyHistoryRepositoryInterface $propertyHistoryRepository,
        UserPropertyHistoryRepositoryInterface $userPropertyHistoryRepository,
        UserPropertyRepositoryInterface $userPropertyRepository
    ) {
        $this->propertyRepository = $propertyRepository;
        $this->propertyHistoryRepository = $propertyHistoryRepository;
        $this->userPropertyHistoryRepository = $userPropertyHistoryRepository;
        $this->userPropertyRepository = $userPropertyRepository;
    }

    public function saveHistoricalContracts()
    {
        $today = Carbon::today()->toDateString();
        $properties = $this->propertyRepository->findBy('expected_end_date_ros', '<=', $today);
        foreach ($properties as $property) {
            DB::beginTransaction();
            try {
                if ($this->isNewHistory($property)) {
                    $currentProperty = $this->createPropertyHistory($property);
                    $this->createUserPropertyHistories($property, $currentProperty);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error guardando histórico de la propiedad ID {$property->id}: " . $e->getMessage());
                return false;
            }
        }
        return true;
    }

    private function isNewHistory($property)
    {
        $existingHistory = $this->propertyHistoryRepository->findBy([
            'property_id' => $property->id,
            'expected_end_date_ros' => $property->expected_end_date_ros,
        ]);
        Log::info("Historial de la propiedad ID {$property->id}: " . $existingHistory->isEmpty());
        return $existingHistory->isEmpty();
    }

    private function createPropertyHistory($property)
    {
        $property->property_id = $property->id;
        return $this->propertyHistoryRepository->create($property->toArray());
    }

    private function createUserPropertyHistories($property, $currentProperty)
    {
        $userProperties = $this->userPropertyRepository->findByProperty($property->id);
        foreach ($userProperties as $key => $userProperty) {
            $user = $userProperty->user;
            $userProperties[$key]->type = $user ? $user->getRoleNames()->first() : null;
            unset($userProperties[$key]->user);
            $this->userPropertyHistoryRepository->create([
                'property_id' =>  $currentProperty->id,
                'user_id' => $userProperties[$key]->property_id,
                'type_user' => $userProperties[$key]->type,
            ]);
        }
    }
}
