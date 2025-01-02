<?php

namespace App\Http\Controllers\Provider;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Provider\ProviderService;
use App\Http\Requests\Provider\StoreProviderRequest;
use App\Http\Requests\Provider\UpdateProviderRequest;
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
                    'providers.address',
                    'providers.coverage_area',
                    'providers.contact_phone',
                    'providers.code_number',
                    'providers.code_country',
                    'providers.contact_email',
                    'providers.service_cost',
                    'providers.status',
                    DB::raw('GROUP_CONCAT(CONCAT(e_provider.id, ":", e_provider.name) ORDER BY e_provider.name ASC SEPARATOR ";") as providers_name'),
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function byProperty()
    {
        try {
            $data['status'] = 1;
            $query = $this->incidentService->getProvidersQuery($data);
            $providers = $query->get(
                [
                    'providers.id',
                    'providers.name',
                ]
            );
            return Response::sendResponse($providers, 'Registros obtenidos con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function store(StoreProviderRequest $request)
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

    public function update(UpdateProviderRequest $request, $id)
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
