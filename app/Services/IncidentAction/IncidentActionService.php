<?php

namespace App\Services\IncidentAction;

use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\IncidentAction\IncidentAction;
use App\Interfaces\IncidentAction\IncidentActionRepositoryInterface;

class IncidentActionService
{
    protected $incidentActionRepository;
    protected $fileService;
    private $listPhotos = ['photo', 'photo1', 'photo2', 'photo3'];
    private $listDocuments = ['document', 'document1'];

    public function __construct(IncidentActionRepositoryInterface $incidentActionRepository, FileService $fileService)
    {
        $this->fileService = $fileService;
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

        return $query;
    }

    public function storeIncident(array $data)
    {
        DB::beginTransaction();
        try {
            $photos = isset($data['photos']) ? $data['photos'] : [];
            unset($data['photo']);
            $data['user_id'] = auth()->user()->id;
            $incidentAction = $this->incidentActionRepository->create($data);
            $this->assignedPhoto($incidentAction, $photos);
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
            $currentIncident = IncidentAction::find($id);
            for ($i = 0; $i <= 5; $i++) {
                $photoField = $i === 0 ? 'photo' : "photo{$i}";
                if (!empty($currentIncident->$photoField)) {
                    $this->fileService->deleteFile(
                        cleanStorageUrl($currentIncident->$photoField, '/storage_incident_action/'),
                        'disk_incident_action'
                    );
                }
            }
            for ($i = 0; $i <= 2; $i++) {
                $documentField = $i === 0 ? 'document' : "document{$i}";
                if (!empty($currentIncident->$documentField)) {
                    $this->fileService->deleteFile(
                        cleanStorageUrl($currentIncident->$documentField, '/storage_incident_action/'),
                        'disk_incident_action'
                    );
                }
            }
            $this->incidentActionRepository->delete($id);
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

    private function assignedPhoto(&$incidentAction, $photos)
    {
        if (isset($photos[0])) {
            $incidentAction->photo = $this->fileService->saveFile($photos[0], 'photo', 'disk_incident_action');
        }
        if (isset($photos[1])) {
            $incidentAction->photo1 = $this->fileService->saveFile($photos[1], 'photo', 'disk_incident_action');
        }
        if (isset($photos[2])) {
            $incidentAction->photo2 = $this->fileService->saveFile($photos[2], 'photo', 'disk_incident_action');
        }
        if (isset($photos[3])) {
            $incidentAction->photo3 = $this->fileService->saveFile($photos[3], 'photo', 'disk_incident_action');
        }
    }

    public function addPhotoIncident($data)
    {
        $flagColumn = null;
        $incidentAction = $this->incidentActionRepository->findById($data['incident_id']);
        $columns = $this->listPhotos;
        foreach ($columns as $column) {
            if (empty($incidentAction->{$column})) {
                $incidentAction->{$column} = $this->fileService->saveFile($data['photo'], 'photo', 'disk_incident_action');
                $flagColumn = $incidentAction->{$column};
                $incidentAction->save();
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
                if ($this->fileService->deleteFile(cleanStorageUrl($matchingColumn[1][$matchingColumn[0]], '/storage_incident_action/'), 'disk_incident_action')) {
                    IncidentAction::where('id', $data['incident_id'])->update([
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
        $incidentAction = IncidentAction::where('id', $id)
            ->where(function ($query) use ($filename) {
                $query->where('photo', 'LIKE', "%$filename%")
                    ->orWhere('photo1', 'LIKE', "%$filename%")
                    ->orWhere('photo2', 'LIKE', "%$filename%")
                    ->orWhere('photo3', 'LIKE', "%$filename%");
            })
            ->first();

        if (!$incidentAction) {
            return null;
        }

        $columns = $this->listPhotos;
        foreach ($columns as $column) {
            if ($incidentAction->$column == $photo) {
                return [$column, $incidentAction->toArray()];
            }
        }
        return null;
    }

    public function addPdfIncident($data)
    {
        $flagColumn = null;
        $pdfs = $data['pdfs'];
        $incidentAction = $this->incidentActionRepository->findById($data['incident_id']);
        $columns = $this->listDocuments;
        foreach ($pdfs as $key => $pdf) {
            foreach ($columns as $column) {
                if (empty($incidentAction->{$column})) {
                    $incidentAction->{$column} = $this->fileService->saveFile($pdf, 'pdf', 'disk_incident_action');
                    $flagColumn = $incidentAction->{$column};
                    $incidentAction->save();
                    break;
                }
            }
        }
        return $flagColumn;
    }

    public function deletePdfIncident($data)
    {
        try {
            $incidentAction = $this->incidentActionRepository->findById($data['incident_id']);
            if ($data['type'] == 'document') {
                $this->fileService->deleteFile(cleanStorageUrl($incidentAction->document, '/storage_incident_action/'), 'disk_incident_action');
                $incidentAction->document = null;
            } else {
                $this->fileService->deleteFile(cleanStorageUrl($incidentAction->document1, '/storage_incident_action/'), 'disk_incident_action');
                $incidentAction->document1 = null;
            }
            $incidentAction->save();
            return $incidentAction;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }
}
