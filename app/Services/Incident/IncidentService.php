<?php

namespace App\Services\Incident;

use App\Models\Incident\Incident;
use App\Services\File\FileService;
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
    protected $fileService;
    private $listPhotos = ['photo', 'photo1', 'photo2', 'photo3'];
    private $listDocuments = ['document', 'document1'];

    public function __construct(IncidentRepositoryInterface $incidentRepository, IncidentProviderRepositoryInterface $incidentProviderRepository, EnumOptionRepositoryInterface $enumOptionRepository, FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->incidentRepository = $incidentRepository;
        $this->incidentProviderRepository = $incidentProviderRepository;
        $this->enumOptionRepository = $enumOptionRepository;
    }

    public function getIncidentsQuery($data = [])
    {
        $query = Incident::query()
            ->leftJoin('incident_provider', 'incident_provider.incident_id', '=', 'incidents.id')
            ->leftJoin('incident_actions', 'incident_actions.incident_id', '=', 'incidents.id')
            ->leftJoin('providers', 'providers.id', '=', 'incident_provider.provider_id')
            ->leftJoin('properties', 'properties.id', '=', 'incidents.property_id')
            ->leftJoin('enum_options as e_ct', 'e_ct.id', '=', 'incidents.incident_type_id')
            ->leftJoin('enum_options as e_st', 'e_st.id', '=', 'incidents.status_id')
            ->leftJoin('enum_options as e_sev', 'e_sev.id', '=', 'incidents.priority_id')
            ->leftJoin('enum_options as e_py', 'e_py.id', '=', 'incidents.payer_id')
            ->leftJoin('enum_options as e_cur', 'e_cur.id', '=', 'incidents.currency_id')
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
                    'e_cur.id',
                    'e_cur.name',
                    'incidents.cost',
                    'incidents.photo',
                    'incidents.photo1',
                    'incidents.photo2',
                    'incidents.photo3',
                    'incidents.created_at',
                    'incidents.updated_at',
                    'incidents.document',
                    'incidents.document1',
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
            $photos = isset($data['photos']) ? $data['photos'] : [];
            unset($data['photo']);
            $data['reported_by'] = auth()->user()->id;
            $providers = $data['providers'];
            $incident = $this->incidentRepository->create($data);
            $this->assignedPhoto($incident, $photos);
            $incident->save();
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
            $data['property_id'] = !isset($data['property_id']) ? null : $data['property_id'];
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
            $currentIncident = Incident::find($id);
            for ($i = 0; $i <= 5; $i++) {
                $photoField = $i === 0 ? 'photo' : "photo{$i}";
                if (!empty($currentIncident->$photoField)) {
                    $this->fileService->deleteFile(
                        cleanStorageUrl($currentIncident->$photoField, '/storage_incident/'),
                        'disk_incident'
                    );
                }
            }
            for ($i = 0; $i <= 2; $i++) {
                $documentField = $i === 0 ? 'document' : "document{$i}";
                if (!empty($currentIncident->$documentField)) {
                    $this->fileService->deleteFile(
                        cleanStorageUrl($currentIncident->$documentField, '/storage_incident/'),
                        'disk_incident'
                    );
                }
            }
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

    private function assignedPhoto(&$incident, $photos)
    {
        if (isset($photos[0])) {
            $incident->photo = $this->fileService->saveFile($photos[0], 'photo', 'disk_incident');
        }
        if (isset($photos[1])) {
            $incident->photo1 = $this->fileService->saveFile($photos[1], 'photo', 'disk_incident');
        }
        if (isset($photos[2])) {
            $incident->photo2 = $this->fileService->saveFile($photos[2], 'photo', 'disk_incident');
        }
        if (isset($photos[3])) {
            $incident->photo3 = $this->fileService->saveFile($photos[3], 'photo', 'disk_incident');
        }
    }

    public function addPhotoIncident($data)
    {
        $flagColumn = null;
        $incident = $this->incidentRepository->findById($data['incident_id']);
        $columns = $this->listPhotos;
        foreach ($columns as $column) {
            if (empty($incident->{$column})) {
                $incident->{$column} = $this->fileService->saveFile($data['photo'], 'photo', 'disk_incident');
                $flagColumn = $incident->{$column};
                $incident->save();
                break;
            }
        }
        return $flagColumn;
    }

    public function deletePhotoIncident($data)
    {
        try {
            $matchingColumn = $this->findPhotoColumn($data['incident_id'], $data['photo']);
            if ($matchingColumn) {
                if ($this->fileService->deleteFile(cleanStorageUrl($matchingColumn[1][$matchingColumn[0]], '/storage_incident/'), 'disk_incident')) {
                    Incident::where('id', $data['incident_id'])->update([
                        $matchingColumn[0] => null
                    ]);
                }
            }
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    private function findPhotoColumn($id, $photo)
    {
        $filename = basename($photo);
        $incident = Incident::where('id', $id)
            ->where(function ($query) use ($filename) {
                $query->where('photo', 'LIKE', "%$filename%")
                    ->orWhere('photo1', 'LIKE', "%$filename%")
                    ->orWhere('photo2', 'LIKE', "%$filename%")
                    ->orWhere('photo3', 'LIKE', "%$filename%");
            })
            ->first();

        if (!$incident) {
            return null;
        }

        $columns = $this->listPhotos;
        foreach ($columns as $column) {
            if ($incident->$column == $photo) {
                return [$column, $incident->toArray()];
            }
        }
        return null;
    }

    public function addPdfIncident($data)
    {
        $flagColumn = null;
        $pdfs = $data['pdfs'];
        $incident = $this->incidentRepository->findById($data['incident_id']);
        $columns = $this->listDocuments;
        foreach ($pdfs as $key => $pdf) {
            foreach ($columns as $column) {
                if (empty($incident->{$column})) {
                    $incident->{$column} = $this->fileService->saveFile($pdf, 'pdf', 'disk_incident');
                    $flagColumn = $incident->{$column};
                    $incident->save();
                    break;
                }
            }
        }
        return $flagColumn;
    }

    public function deletePdfIncident($data)
    {
        try {
            $incident = $this->incidentRepository->findById($data['incident_id']);
            if ($data['type'] == 'document') {
                $this->fileService->deleteFile(cleanStorageUrl($incident->document, '/storage_incident/'), 'disk_incident');
                $incident->document = null;
            } else {
                $this->fileService->deleteFile(cleanStorageUrl($incident->document1, '/storage_incident/'), 'disk_incident');
                $incident->document1 = null;
            }
            $incident->save();
            return $incident;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }
}
