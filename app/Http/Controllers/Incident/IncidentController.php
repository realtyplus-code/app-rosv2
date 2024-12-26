<?php

namespace App\Http\Controllers\Incident;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Incident\IncidentService;
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

    public function index(Request $request)
    {
        try {
            $query = $this->incidentService->getIncidentsQuery($request->all());
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'incidents.id',
                    'incidents.type',
                    'incidents.description',
                    'incidents.date',
                    'properties.id as property_id',
                    'properties.name as property_name',
                    'e_ct.name as type_name',
                    'e_ct.id as type_id',
                    'incidents.created_at',
                    'incidents.updated_at',
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function store(Request $request)
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

    public function update(Request $request, $id)
    {
        try {
            $incident = $this->incidentService->updateIncident($request->all(), $id);
            return Response::sendResponse($incident, 'Registro actualizado con exito.');
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
}
