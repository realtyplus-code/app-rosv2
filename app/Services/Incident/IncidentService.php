<?php

namespace App\Services\Incident;

use App\Models\Incident\Incident;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Incident\IncidentRepositoryInterface;
use App\Interfaces\IncidentProvider\IncidentProviderRepositoryInterface;

class IncidentService
{
    protected $incidentRepository;
    protected $incidentProviderRepository;

    public function __construct(IncidentRepositoryInterface $incidentRepository, IncidentProviderRepositoryInterface $incidentProviderRepository)
    {
        $this->incidentRepository = $incidentRepository;
        $this->incidentProviderRepository = $incidentProviderRepository;
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
            $providers = $data['providers'];
            $incident = $this->incidentRepository->create($data);
            foreach ($providers as $key => $value) {
                $this->incidentProviderRepository->create([
                    'incident_id' => $incident->id,
                    'provider_id' => $value,
                ]);
            }
            DB::commit();
            return $incident;
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
            $incident = $this->incidentRepository->update($id, $data);
            DB::commit();
            return $incident;
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
