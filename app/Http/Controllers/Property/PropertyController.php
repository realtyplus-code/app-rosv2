<?php

namespace App\Http\Controllers\Property;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\ExportDataGrid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
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

    public function view()
    {
        return view('properties.property');
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
                    DB::raw('COUNT(DISTINCT insurances.id) as insurances')
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function byTypeCount()
    {
        try {
            $query = $this->propertyService->getPropertiesTypeQuery();
            $properties = $query->get();
            $actives = $properties->where('status', 1)->count();
            $inactives = $properties->where('status', 2)->count();
            $response = [
                'actives' => $actives,
                'inactives' => $inactives
            ];
            return Response::sendResponse($response, 'Registros obtenidos con exito.');
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

    public function exportExcel(Request $request)
    {
        $currentDate = Carbon::now()->format('Y-m-d H:i:s');

        return Excel::download(new ExportDataGrid([
            'Name',
            'Address',
            'Status',
            'Property Type Name',
            'Owners Name',
            'Tenants Name',
            'Insurances'
        ], $this->getDataToExport($request), [], ''), "User_{$currentDate}.xlsx",);
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = $this->getDataToExport($request);
            $pdf = \PDF::loadView('exports.properties', ['data' => $data]);
            $currentDate = Carbon::now()->format('Y-m-d H:i:s');
            return $pdf->download("User_{$currentDate}.pdf");
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    private function getDataToExport(Request $request)
    {
        $query = $this->propertyService->getPropertiesQuery();
        return renderDataTableExport(
            $query,
            $request,
            [],
            [
                'properties.name',
                'properties.address',
                'properties.status',
                'eo_property_type.name as property_type_name',
                DB::raw('GROUP_CONCAT(user_owner.name ORDER BY user_owner.name ASC SEPARATOR ";") as owners_name'),
                DB::raw('GROUP_CONCAT(user_tenant.name ORDER BY user_tenant.name ASC SEPARATOR ";") as tenants_name'),
                DB::raw('COUNT(DISTINCT insurances.id) as insurances')
            ]
        );
    }
}
