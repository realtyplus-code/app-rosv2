<?php

namespace App\Http\Controllers\Provider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Provider\ProviderService;
use App\Http\Controllers\ResponseController as Response;

class ProviderController extends Controller
{
    protected $incidentService;

    public function __construct(ProviderService $incidentService)
    {
        $this->incidentService = $incidentService;
    }

    public function view()
    {
        return view('providers.provider');
    }

    public function index(Request $request)
    {
        try {
            $query = $this->incidentService->getProvidersQuery($request->all());
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'providers.id',
                    'providers.name',
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
            $incident = $this->incidentService->storeProvider($request->all());
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
            $incident = $this->incidentService->updateProvider($request->all(), $id);
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
            $this->incidentService->deleteProvider($id);
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
