<?php

namespace App\Services\Property;

use App\Models\Property\Property;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\Property\PropertyRepositoryInterface;
use App\Interfaces\UserProperty\UserPropertyRepositoryInterface;


class PropertyService
{
    protected $propertyRepository;
    protected $userPropertyRepository;
    protected $fileService;
    private $listPhotos = ['photo', 'photo1', 'photo2', 'photo3'];
    private $listDocuments = ['document'];

    public function __construct(PropertyRepositoryInterface $propertyRepository, UserPropertyRepositoryInterface $userPropertyRepository, FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->propertyRepository = $propertyRepository;
        $this->userPropertyRepository = $userPropertyRepository;
    }

    public function getPropertiesQuery()
    {
        $query = Property::query()
            ->leftJoin('enum_options as eo_property_type', 'eo_property_type.id', '=', 'properties.property_type_id')
            ->leftJoin('user_properties', 'user_properties.property_id', '=', 'properties.id')
            ->leftJoin('insurances', 'insurances.property_id', '=', 'properties.id')
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
            ->leftJoin('enum_options as ec', 'ec.id', '=', 'properties.country')
            ->leftJoin('enum_options as es', 'es.id', '=', 'properties.state')
            ->leftJoin('enum_options as eci', 'eci.id', '=', 'properties.city')
            ->groupBy([
                'properties.id',
                'insurances.id',
                'incidents.id',
                'properties.name',
                'properties.address',
                'properties.status',
                'eo_property_type.id',
                'eo_property_type.name',
                'properties.photo',
                'properties.photo1',
                'properties.photo2',
                'properties.photo3',
                'properties.document',
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
            ]);

        return $query->distinct();
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
            $data['user_id'] = Auth::id();
            $property = $this->propertyRepository->create($data);
            $this->assignedPhoto($property, $photos);
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
            $currentProperty = Property::find($id);
            for ($i = 0; $i <= 5; $i++) {
                $photoField = $i === 0 ? 'photo' : "photo{$i}";
                if (!empty($currentProperty->$photoField)) {
                    $this->fileService->deleteFile(
                        cleanStorageUrl($currentProperty->$photoField, '/storage_property/'),
                        'disk_property'
                    );
                }
            }
            if (!empty($currentProperty->document)) {
                $this->fileService->deleteFile(
                    cleanStorageUrl($currentProperty->document, '/storage_property/'),
                    'disk_property'
                );
            }
            $this->userPropertyRepository->deleteByProperty($id);
            $this->propertyRepository->delete($id);
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

    private function assignedPhoto(&$property, $photos)
    {
        if (isset($photos[0])) {
            $property->photo = $this->fileService->saveFile($photos[0], 'photo', 'disk_property');
        }
        if (isset($photos[1])) {
            $property->photo1 = $this->fileService->saveFile($photos[1], 'photo', 'disk_property');
        }
        if (isset($photos[2])) {
            $property->photo2 = $this->fileService->saveFile($photos[2], 'photo', 'disk_property');
        }
        if (isset($photos[3])) {
            $property->photo3 = $this->fileService->saveFile($photos[3], 'photo', 'disk_property');
        }
    }

    public function addPhotoProperty($data)
    {
        $flagColumn = null;
        $property = $this->propertyRepository->findById($data['property_id']);
        $columns = $this->listPhotos;
        foreach ($columns as $column) {
            if (empty($property->{$column})) {
                $property->{$column} = $this->fileService->saveFile($data['photo'], 'photo', 'disk_property');
                $flagColumn = $property->{$column};
                $property->save();
                break;
            }
        }
        return $flagColumn;
    }

    public function deletePhotoProperty($data)
    {
        try {
            $matchingColumn = $this->findPhotoColumn($data['property_id'], $data['photo']);
            if ($matchingColumn) {
                if ($this->fileService->deleteFile(cleanStorageUrl($matchingColumn[1][$matchingColumn[0]], '/storage_property/'), 'disk_property')) {
                    Property::where('id', $data['property_id'])->update([
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
        $property = Property::where('id', $id)
            ->where(function ($query) use ($filename) {
                $query->where('photo', 'LIKE', "%$filename%")
                    ->orWhere('photo1', 'LIKE', "%$filename%")
                    ->orWhere('photo2', 'LIKE', "%$filename%")
                    ->orWhere('photo3', 'LIKE', "%$filename%");
            })
            ->first();

        if (!$property) {
            return null;
        }

        $columns = $this->listPhotos;
        foreach ($columns as $column) {
            if ($property->$column == $photo) {
                return [$column, $property->toArray()];
            }
        }
        return null;
    }

    public function addPdfIncident($data)
    {
        $flagColumn = null;
        $pdfs = $data['pdfs'];
        $property = $this->propertyRepository->findById($data['property_id']);
        $columns = $this->listDocuments;
        foreach ($pdfs as $key => $pdf) {
            foreach ($columns as $column) {
                if (empty($property->{$column})) {
                    $property->{$column} = $this->fileService->saveFile($pdf, 'pdf', 'disk_property');
                    $flagColumn = $property->{$column};
                    $property->save();
                    break;
                }
            }
        }
        return $flagColumn;
    }

    public function deletePdfIncident($data)
    {
        try {
            $property = $this->propertyRepository->findById($data['property_id']);
            if ($data['type'] == 'document') {
                $this->fileService->deleteFile(cleanStorageUrl($property->document, '/storage_property/'), 'disk_property');
                $property->document = null;
            }
            $property->save();
            return $property;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }
}
