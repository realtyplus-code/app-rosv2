<?php

namespace App\Services\Incident;

use App\Models\Incident\Incident;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Incident\IncidentRepositoryInterface;
use App\Interfaces\EnumOption\EnumOptionRepositoryInterface;
use App\Interfaces\IncidentProvider\IncidentProviderRepositoryInterface;

class IncidentService
{
    protected $incidentRepository;
    protected $incidentProviderRepository;
    protected $enumOptionRepository;

    public function __construct(IncidentRepositoryInterface $incidentRepository, IncidentProviderRepositoryInterface $incidentProviderRepository, EnumOptionRepositoryInterface $enumOptionRepository)
    {
        $this->incidentRepository = $incidentRepository;
        $this->incidentProviderRepository = $incidentProviderRepository;
        $this->enumOptionRepository = $enumOptionRepository;
    }

    public function getIncidentsQuery()
    {
        $query = Incident::query()
            ->leftJoin('incident_provider', 'incident_provider.incident_id', '=', 'incidents.id')
            ->leftJoin('providers', 'providers.id', '=', 'incident_provider.provider_id')
            ->leftJoin('properties', 'properties.id', '=', 'incidents.property_id')
            ->leftJoin('enum_options as e_ct', 'e_ct.id', '=', 'incidents.incident_type_id')
            ->leftJoin('enum_options as e_st', 'e_st.id', '=', 'incidents.status_id')
            ->leftJoin('enum_options as e_sev', 'e_sev.id', '=', 'incidents.priority_id')
            ->leftJoin('enum_options as e_py', 'e_py.id', '=', 'incidents.payer_id')
            ->leftJoin('users', 'users.id', '=', 'incidents.reported_by')
            ->groupBy(
                [
                    'incidents.id',
                    'incidents.description',
                    'properties.id',
                    'properties.name',
                    'incidents.report_date',
                    'e_st.id',
                    'e_st.name',
                    'users.id',
                    'users.name',
                    'e_sev.id',
                    'e_sev.name',
                    'e_ct.id',
                    'e_ct.name',
                    'e_py.id',
                    'e_py.name',
                    'incidents.cost',
                    'incidents.created_at',
                    'incidents.updated_at',
                ]
            );

        if (isset($data['property_id'])) {
            $query->where('properties.id', $data['property_id']);
        }

        return $query;
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
            $data['reported_by'] = auth()->user()->id;
            $providers = $data['providers'];
            $incident = $this->incidentRepository->update($id, $data);
            $this->incidentProviderRepository->deleteByIncident($id);
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

    public function updateByStatusIncident(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $response = $this->enumOptionRepository->findByName($data['status']);
            if (!$response) {
                throw new \Exception('Status no found');
            }
            $data['status_id'] = $response->id;
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
            $this->incidentProviderRepository->deleteByIncident($id);
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function showIncident($id)
    {
        try {
            return $this->incidentRepository->findById($id);
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function getIncidentsTypeQuery()
    {
        $query = Incident::query()
            ->leftJoin('enum_options as e_ct', 'e_ct.id', '=', 'incidents.incident_type_id')
            ->groupBy('e_ct.name');

        return $query->distinct();
    }
}
