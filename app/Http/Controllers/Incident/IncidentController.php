<?php

namespace App\Http\Controllers\Incident;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Incident\IncidentService;

use App\Http\Requests\Incident\ValidatePdfRequest;
use App\Http\Requests\Incident\StoreIncidentRequest;
use App\Http\Requests\Incident\ValidatePhotoRequest;
use App\Http\Requests\Incident\UpdateIncidentRequest;
use App\Http\Controllers\ResponseController as Response;

class IncidentController extends Controller
{
    protected $incidentService;

    public function __construct(IncidentService $incidentService)
    {
        $this->incidentService = $incidentService;
    }

    public function view()
    {
        return view('incidents.incident');
    }

    public function viewKanban()
    {
        return view('incidents.incident_kanban');
    }

    public function index(Request $request)
    {
        try {
            $role = Auth::user()->getRoleNames()[0];
            if ($role !== 'admin' && !$request->has('property_id')) {
                return Response::sendError('The property field is required for non-admin users', 400);
            }

            $query = $this->incidentService->getIncidentsQuery($request->all());
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'incidents.id',
                    'incidents.description',
                    'properties.id as property_id',
                    'properties.name as property_name',
                    'incidents.report_date',
                    'e_st.id as status_id',
                    'e_st.name as status_name',
                    'users.id as reported_by_id',
                    'users.name as reported_by_name',
                    'e_sev.id as priority_id',
                    'e_sev.name as priority_name',
                    'e_ct.id as type_id',
                    'e_ct.name as type_name',
                    'e_py.id as payer_id',
                    'e_py.name as payer_name',
                    'incidents.cost',
                    'incidents.photo',
                    'incidents.photo1',
                    'incidents.photo2',
                    'incidents.photo3',
                    'incidents.document',
                    'incidents.document1',
                    'incidents.created_at',
                    'incidents.updated_at',
                    DB::raw('GROUP_CONCAT(CONCAT(providers.id, ":", providers.name) ORDER BY providers.name ASC SEPARATOR ";") as provider_name'),
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
            $query = $this->incidentService->getIncidentsTypeQuery();
            $response = $query->get([
                'e_ct.name as type_name',
                DB::raw('COUNT(incidents.id) as count'),
            ]);
            return Response::sendResponse($response, 'Registros obtenidos con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function store(StoreIncidentRequest $request)
    {
        try {
            $incident = $this->incidentService->storeIncident($request->all());
            return Response::sendResponse($incident, 'Registro creado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function update(UpdateIncidentRequest $request, $id)
    {
        try {
            $type = $request->get('type') ?? null;
            switch ($type) {
                case 'status':
                    $incident = $this->incidentService->updateByStatusIncident($request->all(), $id);
                    break;
                default:
                    $incident = $this->incidentService->updateIncident($request->all(), $id);
                    break;
            }

            return Response::sendResponse($incident, 'Registro actualizado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function show($id)
    {
        try {
            $incident = $this->incidentService->showIncident($id);
            return Response::sendResponse($incident, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->incidentService->deleteIncident($id);
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function addPhoto(ValidatePhotoRequest $request)
    {
        try {
            $photo = $this->incidentService->addPhotoIncident($request->all());
            return Response::sendResponse($photo, 'Registro añadido con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroyPhoto(Request $request)
    {
        try {
            $this->incidentService->deletePhotoIncident($request->all());
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function addPdf(ValidatePdfRequest $request)
    {
        try {
            $pdf = $this->incidentService->addPdfIncident($request->all());
            return Response::sendResponse($pdf, 'Registro añadido con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroyPdf(Request $request)
    {
        try {
            $this->incidentService->deletePdfIncident($request->all());
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
