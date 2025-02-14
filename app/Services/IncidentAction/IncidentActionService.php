<?php

namespace App\Services\IncidentAction;

use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\IncidentAction\IncidentAction;
use App\Services\Attachment\AttachmentService;
use App\Interfaces\IncidentAction\IncidentActionRepositoryInterface;

class IncidentActionService
{
    protected $incidentActionRepository;
    protected $fileService;
    protected $attachmentService;
    private $disk = 'disk_incident_action';
    private $listPhotos = ['photo', 'photo1', 'photo2', 'photo3'];
    private $listDocuments = ['document', 'document1'];

    public function __construct(IncidentActionRepositoryInterface $incidentActionRepository, AttachmentService $attachmentService, FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->attachmentService = $attachmentService;
        $this->incidentActionRepository = $incidentActionRepository;
    }

    public function getIncidentActionQuery($data = [])
    {
        $query = IncidentAction::query()
            ->leftJoin('incidents', 'incidents.id', '=', 'incident_actions.incident_id');

        $query->leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'incident_actions.responsible_user_id')
                ->where('incident_actions.responsible_user_type', '=', 'USER');
        });

        $query->leftJoin('providers', function ($join) {
            $join->on('providers.id', '=', 'incident_actions.responsible_user_id')
                ->where('incident_actions.responsible_user_type', '=', 'PROVIDER');
        });

        $query->leftJoin('users as user_log', 'user_log.id', '=', 'incident_actions.user_id')
            ->leftJoin('enum_options as eat', 'eat.id', '=', 'incident_actions.action_type_id')
            ->leftJoin('enum_options as es', 'es.id', '=', 'incident_actions.status_id')
            ->leftJoin('enum_options as ec', 'ec.id', '=', 'incident_actions.currency_id');

        if (isset($data['incident_id'])) {
            $query->where('incidents.id', $data['incident_id']);
        }

        $this->getByUserRol($query);

        return $query;
    }

    private function getByUserRol(&$query)
    {
        $userId = Auth::id();
        switch (Auth::user()->getRoleNames()[0]) {
            case 'owner':
            case 'tenant':
                $query->leftJoin('properties', 'properties.id', '=', 'incidents.property_id')
                    ->whereExists(function ($subQuery) use ($userId) {
                        $subQuery->select(DB::raw(1))
                            ->from('user_properties')
                            ->whereRaw('user_properties.property_id = properties.id')
                            ->where('user_properties.user_id', $userId);
                    });
                $query->where('eat.name', '!=', 'closure');
                break;
            case 'provider':
                $query->where('providers.id', $userId);
                break;
            case 'ros_client_manager':
                $query->leftJoin('properties', 'properties.id', '=', 'incidents.property_id')
                    ->whereIn('properties.client_ros_id', function ($query) use ($userId) {
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
            $data['user_id'] = auth()->user()->id;
            $incidentAction = $this->incidentActionRepository->create($data);
            $this->assignAttachments($incidentAction, $photos);
            $incidentAction->save();
            DB::commit();
            return $incidentAction;
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
            $data['action_cost'] = $data['action_cost'] ?? null;
            $incidentAction = $this->incidentActionRepository->update($id, $data);
            DB::commit();
            return $incidentAction;
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
            if ($this->incidentActionRepository->delete($id)) {
                $this->attachmentService->deleteByAttachable($id, IncidentAction::class, $this->disk);
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
            return $this->incidentActionRepository->findById($id);
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    private function assignAttachments(&$incidentAction, $photos)
    {
        foreach ($this->listPhotos as $key => $value) {
            if (isset($photos[$key])) {
                $originalName = $photos[$key]->getClientOriginalName();
                $filePath = $this->fileService->saveFile($photos[$key], 'photo', $this->disk);
                $this->attachmentService->store([
                    'attachable_id' => $incidentAction->id,
                    'attachable_type' => IncidentAction::class,
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
            'attachable_type' => IncidentAction::class,
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
                    'attachable_type' => IncidentAction::class,
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
