<?php

namespace App\Services\Incident;

use Illuminate\Support\Facades\DB;
use App\Models\Incident\Incident;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Incident\IncidentRepositoryInterface;

class IncidentService
{
    protected $incidentRepository;

    public function __construct(IncidentRepositoryInterface $incidentRepository)
    {
        $this->incidentRepository = $incidentRepository;
    }

    public function getIncidentsQuery()
    {
        $query = Incident::query()
            ->leftJoin('properties', 'properties.id', '=', 'incidents.property_id')
            ->leftJoin('enum_options as e_ct', 'e_ct.id', '=', 'incidents.type_id');
    
        return $query->distinct();
    }

    public function storeIncident(array $data)
    {
        DB::beginTransaction();
        try {
            $data['reported_by'] = auth()->user()->id;
            $insurance = $this->incidentRepository->create($data);
            DB::commit();
            return $insurance;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function updateIncident(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $insurance = $this->incidentRepository->update($id, $data);
            DB::commit();
            return $insurance;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteIncident($id)
    {
        try {
            $this->incidentRepository->delete($id);
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }
}
