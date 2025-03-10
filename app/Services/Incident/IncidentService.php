<?php

namespace App\Services\Incident;

use App\Models\Incident\Incident;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\Attachment\AttachmentService;
use App\Interfaces\Incident\IncidentRepositoryInterface;
use App\Interfaces\EnumOption\EnumOptionRepositoryInterface;
use App\Interfaces\IncidentProvider\IncidentProviderRepositoryInterface;

class IncidentService
{
    protected $incidentRepository;
    protected $incidentProviderRepository;
    protected $enumOptionRepository;
    protected $attachmentService;
    protected $fileService;
    private $disk = 'disk_incident';
    private $listPhotos = ['photo', 'photo1', 'photo2', 'photo3'];
    private $listDocuments = ['document', 'document1'];

    public function __construct(
        IncidentRepositoryInterface $incidentRepository,
        IncidentProviderRepositoryInterface $incidentProviderRepository,
        EnumOptionRepositoryInterface $enumOptionRepository,
        AttachmentService $attachmentService,
        FileService $fileService
    ) {
        $this->fileService = $fileService;
        $this->attachmentService = $attachmentService;
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
            ->leftJoin('users', 'users.id', '=', 'incidents.reported_by');

        $this->getByUserRol($query);

        if (isset($data['property_id'])) {
            $query->where('properties.id', $data['property_id']);
        }

        if (isset($data['status'])) {
            $data['status'] = urldecode($data['status']);
            $query->where('e_st.name', $data['status']);
        }

        $query->orderBy('incidents.report_date', 'desc');

        $query->groupBy(
            [
                'incidents.id',
                'incidents.description',
                'properties.id',
                'properties.name',
                'properties.country',
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
                'incidents.created_at',
                'incidents.updated_at',
            ]
        );

        return $query;
    }

    private function getByUserRol(&$query)
    {
        $userId = Auth::id();
        switch (Auth::user()->getRoleNames()[0]) {
            case 'owner':
            case 'tenant':
                $query->whereExists(function ($subQuery) use ($userId) {
                    $subQuery->select(DB::raw(1))
                        ->from('user_properties')
                        ->whereRaw('user_properties.property_id = properties.id')
                        ->where('user_properties.user_id', $userId);
                });
                break;
            case 'provider':
                $query->whereExists(function ($subQuery) use ($userId) {
                    $subQuery->select(DB::raw(1))
                        ->from('incident_provider')
                        ->where('incident_provider.provider_id', $userId)
                        ->whereRaw('incident_provider.incident_id = incidents.id');
                })
                ->orWhereExists(function ($subQuery) use ($userId) {
                    $subQuery->select(DB::raw(1))
                        ->from('incident_actions')
                        ->where('incident_actions.responsible_user_id', $userId)
                        ->whereRaw('incident_actions.incident_id = incidents.id');
                });
                break;
            case 'ros_client':
                $query->where('properties.client_ros_id', $userId);
                break;
            case 'ros_client_manager':
                $query->whereIn('properties.client_ros_id', function ($query) use ($userId) {
                    $query->select('users_relations.user_id')
                        ->from('users_relations')
                        ->where('users_relations.user_id_related', $userId);
                });
                break;
            default:
                break;
        }
    }

    public function storeIncident(array $data)
    {
        DB::beginTransaction();
        try {
            $photos = isset($data['photos']) ? $data['photos'] : [];
            unset($data['photo']);
            $data['reported_by'] = auth()->user()->id;
            $providers = $data['providers'] ?? [];
            $data['currency_id'] = $data['currency_id'] ?? null;
            $data['cost'] = $data['cost'] ?? null;
            $data['report_date'] = $data['report_date'] ?? now();

            if(!isset($data['status_id'])) {
                $data['status_id'] =  $this->enumOptionRepository->findByName('opened')->id;
            }

            $incident = $this->incidentRepository->create($data);
            $this->assignAttachments($incident, $photos);
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
            $providers = $data['providers'] ?? [];
            $data['currency_id'] = $data['currency_id'] ?? null;
            $data['cost'] = $data['cost'] ?? null;
            $data['property_id'] = !isset($data['property_id']) ? null : $data['property_id'];

            $incident = $this->incidentRepository->update($id, $data);
            $this->incidentProviderRepository->deleteByIncident($id);

            foreach ($providers as $key => $value) {
                Log::info($value);
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
            $this->incidentProviderRepository->deleteByIncident($id);
            if ($this->incidentRepository->delete($id)) {
                $this->attachmentService->deleteByAttachable($id, Incident::class, $this->disk);
            }
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
            ->leftJoin('properties', 'properties.id', '=', 'incidents.property_id')
            ->leftJoin('enum_options as e_st', 'e_st.id', '=', 'incidents.status_id');

        $this->getByUserRol($query);

        $query->groupBy('e_st.name');
        
        return $query->distinct();
    }

    private function assignAttachments(&$incident, $photos)
    {
        foreach ($this->listPhotos as $key => $value) {
            if (isset($photos[$key])) {
                $originalName = $photos[$key]->getClientOriginalName();
                $filePath = $this->fileService->saveFile($photos[$key], 'photo', $this->disk);
                $this->attachmentService->store([
                    'attachable_id' => $incident->id,
                    'attachable_type' => Incident::class,
                    'file_path' => $filePath,
                    'file_type' => 'PHOTO',
                    'name' => $originalName,
                ]);
            }
        }
    }

    public function addPhotoIncident($data)
    {
        $originalName = $data['photo']->getClientOriginalName();
        $filePath = $this->fileService->saveFile($data['photo'], 'photo', $this->disk);
        $attachment = $this->attachmentService->store([
            'attachable_id' => $data['incident_id'],
            'attachable_type' => Incident::class,
            'file_path' => $filePath,
            'file_type' => 'PHOTO',
            'name' => $originalName,
        ]);
        $attachment->file_path = Storage::disk($this->disk)->url($attachment->file_path);
        return $attachment;
    }

    public function deletePhotoIncident($data)
    {
        try {
            $attachment = $this->attachmentService->getById($data['attachment_id']);
            if ($this->fileService->deleteFile($attachment->file_path, $this->disk)) {
                $this->attachmentService->delete($data['attachment_id']);
            }
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function addPdf($data)
    {
        foreach ($this->listDocuments as $key => $value) {
            if (isset($data['pdfs'][$key])) {
                $originalName = $data['pdfs'][$key]->getClientOriginalName();
                $filePath = $this->fileService->saveFile($data['pdfs'][$key], 'pdf', $this->disk);
                $this->attachmentService->store([
                    'attachable_id' => $data['incident_id'],
                    'attachable_type' => Incident::class,
                    'file_path' => $filePath,
                    'file_type' => 'PDF',
                    'name' => $originalName,
                ]);
            }
        }
    }

    public function deletePdf($data)
    {
        try {
            $attachment = $this->attachmentService->getById($data['attachment_id']);
            if ($this->fileService->deleteFile($attachment->file_path, $this->disk)) {
                $this->attachmentService->delete($data['attachment_id']);
            }
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }
}
