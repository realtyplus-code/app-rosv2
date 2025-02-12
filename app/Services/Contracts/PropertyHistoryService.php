<?php

namespace App\Services\Contracts;

use Carbon\Carbon;
use App\Models\Property;
use App\Models\PropertyHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Property\PropertyRepositoryInterface;
use App\Interfaces\Contract\PropertyHistoryRepositoryInterface;

class PropertyHistoryService
{
    protected $propertyRepository;
    protected $propertyHistoryRepository;

    public function __construct(
        PropertyRepositoryInterface $propertyRepository,
        PropertyHistoryRepositoryInterface $propertyHistoryRepository
    ) {
        $this->propertyRepository = $propertyRepository;
        $this->propertyHistoryRepository = $propertyHistoryRepository;
    }

    public function saveHistoricalContracts()
    {
        $today = Carbon::today()->toDateString();
        $properties = $this->propertyRepository->findBy('expected_end_date_ros', '<=', $today);
        foreach ($properties as $property) {
            DB::beginTransaction();
            try {
                $property->property_id = $property->id;
                $this->propertyHistoryRepository->create($property->toArray());
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error guardando histórico de la propiedad ID {$property->id}: " . $e->getMessage());
            }
        }
    }
}
