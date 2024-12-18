<?php

namespace App\Http\Controllers\Property;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Property\PropertyService;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Controllers\ResponseController as Response;

class PropertyController extends Controller
{
    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function index(Request $request)
    {
        try {
            $query = $this->propertyService->getPropertiesQuery();
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'properties.id',
                    'properties.name',
                    'properties.address',
                    'properties.status',
                    'eo_property_type.id as property_type_id',
                    'eo_property_type.name as property_type_name',
                    'properties.photo',
                    'properties.photo1',
                    'properties.photo2',
                    'properties.photo3',
                    DB::raw('GROUP_CONCAT(CONCAT(user_owner.id, ":", user_owner.name) ORDER BY user_owner.name ASC SEPARATOR ";") as owners_name'),
                    DB::raw('GROUP_CONCAT(CONCAT(user_tenant.id, ":", user_tenant.name) ORDER BY user_tenant.name ASC SEPARATOR ";") as tenants_name'),
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function store(StorePropertyRequest $request)
    {
        try {
            $property = $this->propertyService->storeProperty($request->all());
            return Response::sendResponse($property, 'Registro creado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function show($id)
    {
        try {
            $property = $this->propertyService->showProperty($id);
            return Response::sendResponse($property, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $property = $this->propertyService->updateProperty($request->all(), $id);
            return Response::sendResponse($property, 'Registro actualizado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->propertyService->deleteProperty($id);
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function addPhoto(Request $request)
    {
        try {
            $photo = $this->propertyService->addPhotoProperty($request->all());
            return Response::sendResponse($photo, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroyPhoto(Request $request)
    {
        try {
            $this->propertyService->deletePhotoProperty($request->all());
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
