<?php

namespace App\Http\Controllers\Property;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\ExportDataGrid;
use App\Models\Property\Property;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\Property\PropertyService;
use App\Http\Requests\Property\ValidatePdfRequest;
use App\Http\Requests\Property\StorePropertyRequest;
use App\Http\Requests\Property\ValidatePhotoRequest;
use App\Http\Requests\Property\UpdatePropertyRequest;
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
            $query = $this->propertyService->getPropertiesQuery($request->all());
            $response = renderDataTable(
                $query,
                $request,
                [],
                [
                    'properties.id',
                    'properties.name',
                    'properties.address',
                    'properties.status',
                    'ec.id as country_id',
                    'es.id as state_id',
                    'eci.id as city_id',
                    'ec.name as country_name',
                    'es.name as state_name',
                    'eci.name as city_name',
                    'eo_property_type.id as property_type_id',
                    'eo_property_type.name as property_type_name',
                    'properties.created_at',
                    'properties.expected_end_date_ros',
                    'users.id as log_user_id',
                    'users.name as log_user_name',
                    'user_ros.id as user_ros_id',
                    'user_ros.name as user_ros_name',
                    DB::raw('GROUP_CONCAT(DISTINCT CONCAT(user_owner.id, ":", user_owner.name) ORDER BY user_owner.name ASC SEPARATOR ";") as owners_name'),
                    DB::raw('GROUP_CONCAT(DISTINCT CONCAT(user_tenant.id, ":", user_tenant.name) ORDER BY user_tenant.name ASC SEPARATOR ";") as tenants_name'),
                    DB::raw('COUNT(DISTINCT incidents.id) as incidents'),
                    DB::raw('COUNT(DISTINCT insurances.id) as insurances')
                ]
            );
            $response = attachFilesToProperties($response, ['PHOTO' => 'photos', 'PDF' => 'document'], Property::class, 'disk_property');
            return $response;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function byTypeCount()
    {
        try {
            $query = $this->propertyService->getPropertiesTypeQuery();
            $response = $query->get([
                DB::raw('CASE WHEN properties.status = 1 THEN "active" WHEN properties.status = 2 THEN "inactive" ELSE "unknown" END as type_name'),
                DB::raw('COUNT(properties.id) as count'),
            ]);
            return Response::sendResponse($response, __('messages.controllers.success.records_fetched_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function byUnique($idInsurance = null)
    {
        try {
            $query = $this->propertyService->getPropertiesTypeQuery($idInsurance, true);
            $response = $query->get([
                'properties.id',
                'properties.name',
            ]);
            return Response::sendResponse($response, __('messages.controllers.success.records_fetched_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function byId($id)
    {
        try {
            $query = $this->propertyService->getPropertiesQuery(null,$id);
            $response = $query->get([
                'properties.id',
                'properties.name',
            ]);
            return Response::sendResponse($response, __('messages.controllers.success.records_fetched_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function store(StorePropertyRequest $request)
    {
        try {
            $property = $this->propertyService->storeProperty($request->all());
            return Response::sendResponse($property, __('messages.controllers.success.record_created_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function show($id)
    {
        try {
            $property = $this->propertyService->showProperty($id);
            return Response::sendResponse($property, __('messages.controllers.success.record_fetched_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function update(UpdatePropertyRequest $request, $id)
    {
        try {
            $property = $this->propertyService->updateProperty($request->all(), $id);
            return Response::sendResponse($property, __('messages.controllers.success.record_updated_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->propertyService->deleteProperty($id);
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function addPhoto(ValidatePhotoRequest $request)
    {
        try {
            $photo = $this->propertyService->addPhotoProperty($request->all());
            return Response::sendResponse($photo, 'Foto agregada con exito.');
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroyPhoto(Request $request)
    {
        try {
            $this->propertyService->deletePhotoProperty($request->all());
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function exportExcel(Request $request)
    {
        $currentDate = Carbon::now()->format('Y-m-d H:i:s');

        return Excel::download(new ExportDataGrid([
            'Name',
            'Address',
            'Status',
            'Country',
            'State',
            'City',
            'Property Type Name',
            'Created At',
            'Expected End Date ROS',
            'Log User Name',
            'Owners Name',
            'Tenants Name',
            'Incidents'
        ], $this->getDataToExport($request), [], ''), "User_{$currentDate}.xlsx",);
    }

    public function exportPdf(Request $request)
    {
        try {
            $data = $this->getDataToExport($request);
            $logoPath = public_path('img/rentalcolorb.svg');
            $pdf = \PDF::loadView('exports.properties', ['data' => $data, 'logoPath' => $logoPath])
                ->setPaper('A3', 'landscape');
            $currentDate = Carbon::now()->format('Y-m-d H:i:s');
            return $pdf->download("User_{$currentDate}.pdf");
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
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
                DB::raw('CASE WHEN properties.status = 1 THEN "Active" WHEN properties.status = 2 THEN "Inactive" ELSE "unknown" END as status'),
                'ec.name as country',
                'es.name as state',
                'eci.name as city',
                'eo_property_type.name as property_type_name',
                'properties.created_at',
                'properties.expected_end_date_ros',
                'users.name as log_user_name',
                DB::raw('GROUP_CONCAT(DISTINCT user_owner.name ORDER BY user_owner.name ASC SEPARATOR ";") as owners_name'),
                DB::raw('GROUP_CONCAT(DISTINCT user_tenant.name ORDER BY user_tenant.name ASC SEPARATOR ";") as tenants_name'),
                DB::raw('COUNT(DISTINCT incidents.id) as incidents')
            ]
        );
    }

    public function addPdf(ValidatePdfRequest $request)
    {
        try {
            $pdf = $this->propertyService->addPdf($request->all());
            return Response::sendResponse($pdf, __('messages.controllers.success.record_added_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroyPdf(Request $request)
    {
        try {
            $this->propertyService->deletePdf($request->all());
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
