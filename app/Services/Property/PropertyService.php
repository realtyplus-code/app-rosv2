<?php

namespace App\Services\Property;

use App\Models\Attachment;
use App\Models\Property\Property;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\In;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\Attachment\AttachmentService;
use App\Interfaces\Property\PropertyRepositoryInterface;
use App\Interfaces\UserProperty\UserPropertyRepositoryInterface;
use App\Interfaces\InsuranceProperty\InsurancePropertyRepositoryInterface;

class PropertyService
{
    protected $propertyRepository;
    protected $userPropertyRepository;
    protected $insurancePropertyRepository;
    protected $attachmentService;
    protected $fileService;
    private $disk = 'disk_property';
    private $listPhotos = ['photo', 'photo1', 'photo2', 'photo3'];
    private $listDocuments = ['document'];

    public function __construct(
        PropertyRepositoryInterface $propertyRepository,
        UserPropertyRepositoryInterface $userPropertyRepository,
        InsurancePropertyRepositoryInterface $insurancePropertyRepository,
        AttachmentService $attachmentService,
        FileService $fileService
    ) {
        $this->fileService = $fileService;
        $this->attachmentService = $attachmentService;
        $this->propertyRepository = $propertyRepository;
        $this->userPropertyRepository = $userPropertyRepository;
        $this->insurancePropertyRepository = $insurancePropertyRepository;
    }

    public function getPropertiesQuery()
    {
        $query = Property::query()
            ->leftJoin('enum_options as eo_property_type', 'eo_property_type.id', '=', 'properties.property_type_id')
            ->leftJoin('user_properties', 'user_properties.property_id', '=', 'properties.id')
            ->leftJoin('incidents', 'incidents.property_id', '=', 'properties.id')
            ->leftJoin('users as user_owner', function ($join) {
                $join->on('user_owner.id', '=', 'user_properties.user_id')
                    ->join('model_has_roles as mhr_owner', 'mhr_owner.model_id', '=', 'user_owner.id')
                    ->join('roles as r_owner', 'r_owner.id', '=', 'mhr_owner.role_id')
                    ->where('r_owner.name', '=', 'owner')
                    ->where('mhr_owner.model_type', '=', 'App\\Models\\User');
            })
            ->leftJoin('users as user_tenant', function ($join) {
                $join->on('user_tenant.id', '=', 'user_properties.user_id')
                    ->join('model_has_roles as mhr_tenant', 'mhr_tenant.model_id', '=', 'user_tenant.id')
                    ->join('roles as r_tenant', 'r_tenant.id', '=', 'mhr_tenant.role_id')
                    ->where('r_tenant.name', '=', 'tenant')
                    ->where('mhr_tenant.model_type', '=', 'App\\Models\\User');
            })
            ->leftJoin('users', 'users.id', '=', 'properties.user_id')
            ->leftJoin('users as user_ros', 'user_ros.id', '=', 'properties.client_ros_id')
            ->leftJoin('enum_options as ec', 'ec.id', '=', 'properties.country')
            ->leftJoin('enum_options as es', 'es.id', '=', 'properties.state')
            ->leftJoin('enum_options as eci', 'eci.id', '=', 'properties.city')
            ->leftJoin('insurance_property as ip', 'ip.property_id', '=', 'properties.id')
            ->leftJoin('insurances', 'insurances.id', '=', 'ip.insurance_id');

        $this->getByUserRol($query);

        $query->groupBy([
            'properties.id',
            'incidents.id',
            'properties.name',
            'properties.address',
            'properties.status',
            'eo_property_type.id',
            'eo_property_type.name',
            'ec.id',
            'es.id',
            'eci.id',
            'ec.name',
            'es.name',
            'eci.name',
            'properties.created_at',
            'properties.expected_end_date_ros',
            'users.id',
            'users.name',
            'user_ros.id',
            'user_ros.name',
            'insurances.id'
        ]);

        return $query->distinct();
    }

    private function getByUserRol(&$query)
    {
        switch (Auth::user()->getRoleNames()[0]) {
            case 'owner':
            case 'tenant':
                $userId = Auth::id();
                $query->whereExists(function ($subQuery) use ($userId) {
                    $subQuery->select(DB::raw(1))
                        ->from('user_properties')
                        ->whereRaw('user_properties.property_id = properties.id')
                        ->where('user_properties.user_id', $userId);
                });
                break;
            default:
                $query->where('properties.user_id', Auth::id());
                break;
        }
    }

    public function getPropertiesTypeQuery()
    {
        $query = Property::query();
        return $query->distinct();
    }

    public function storeProperty(array $data)
    {
        DB::beginTransaction();
        try {
            $photos = $data['photos'];
            unset($data['photo']);
            $owners = $data['owners'];
            $tenants = [];
            if (isset($data['tenants'])) {
                $tenants = $data['tenants'];
            }
            // proceso de creación de la propiedad
            $data['user_id'] = Auth::id();
            $data['expected_end_date_ros'] = $data['expected_end_date_ros'] ?? null;
            $data['client_ros_id'] = $data['client_ros_id'] ?? null;
            $property = $this->propertyRepository->create($data);
            $this->assignAttachments($property, $photos);
            $property->save();
            foreach ($owners as $key => $value) {
                $this->userPropertyRepository->create([
                    'property_id' => $property->id,
                    'user_id' => $value,
                ]);
            }
            foreach ($tenants as $key => $value) {
                $this->userPropertyRepository->create([
                    'property_id' => $property->id,
                    'user_id' => $value,
                ]);
            }
            if (!empty($data['insurances'])) {
                $this->insurancePropertyRepository->create([
                    'property_id' => $property->id,
                    'insurance_id' => $data['insurances']
                ]);
            }
            DB::commit();
            return $property;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function updateProperty(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $owners = $data['owners'];
            $tenants = [];
            if (isset($data['tenants'])) {
                $tenants = $data['tenants'];
            }
            // proceso de actualización de la propiedad
            $data['expected_end_date_ros'] = $data['expected_end_date_ros'] ?? null;
            $data['client_ros_id'] = $data['client_ros_id'] ?? null;
            $property = $this->propertyRepository->update($id, $data);
            $this->userPropertyRepository->deleteByProperty($id);
            foreach ($owners as $key => $value) {
                $this->userPropertyRepository->create([
                    'property_id' => $property->id,
                    'user_id' => $value,
                ]);
            }
            foreach ($tenants as $key => $value) {
                $this->userPropertyRepository->create([
                    'property_id' => $property->id,
                    'user_id' => $value,
                ]);
            }
            $this->insurancePropertyRepository->deleteByProperty($id);
            if (!empty($data['insurances'])) {
                $this->insurancePropertyRepository->create([
                    'property_id' => $property->id,
                    'insurance_id' => $data['insurances']
                ]);
            }
            DB::commit();
            return $property;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteProperty($id)
    {
        try {
            $this->userPropertyRepository->deleteByProperty($id);
            if ($this->propertyRepository->delete($id)) {
                $this->attachmentService->deleteByAttachable($id, Property::class, $this->disk);
            }
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function showProperty($id)
    {
        try {
            return $this->propertyRepository->findById($id);
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    private function assignAttachments(&$property, $photos)
    {
        foreach ($this->listPhotos as $key => $value) {
            if (isset($photos[$key])) {
                $filePath = $this->fileService->saveFile($photos[$key], 'photo', $this->disk);
                $this->attachmentService->store([
                    'attachable_id' => $property->id,
                    'attachable_type' => Property::class,
                    'file_path' => $filePath,
                    'file_type' => 'PHOTO',
                ]);
            }
        }
    }

    public function addPhotoProperty($data)
    {
        $filePath = $this->fileService->saveFile($data['photo'], 'photo', $this->disk);
        $attachment = $this->attachmentService->store([
            'attachable_id' => $data['property_id'],
            'attachable_type' => Property::class,
            'file_path' => $filePath,
            'file_type' => 'PHOTO',
        ]);
        $attachment->file_path = Storage::disk($this->disk)->url($attachment->file_path);
        return $attachment;
    }

    public function deletePhotoProperty($data)
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
                $filePath = $this->fileService->saveFile($data['pdfs'][$key], 'pdf', $this->disk);
                $this->attachmentService->store([
                    'attachable_id' => $data['property_id'],
                    'attachable_type' => Property::class,
                    'file_path' => $filePath,
                    'file_type' => 'PDF',
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
