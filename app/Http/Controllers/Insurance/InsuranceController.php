<?php

namespace App\Http\Controllers\Insurance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Insurance\InsuranceService;
use App\Http\Requests\Insurance\StoreInsuranceRequest;
use App\Http\Controllers\ResponseController as Response;

class InsuranceController extends Controller
{
    protected $insuranceService;

    public function __construct(InsuranceService $insuranceService)
    {
        $this->insuranceService = $insuranceService;
    }

    public function index(Request $request)
    {
        try {
            $query = $this->insuranceService->getInsurancesQuery();
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'insurances.id',
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function store(StoreInsuranceRequest $request)
    {
        try {
            $insurance = $this->insuranceService->storeInsurance($request->all());
            return Response::sendResponse($insurance, 'Registro creado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function show($id)
    {
        try {
            $insurance = $this->insuranceService->showInsurance($id);
            return Response::sendResponse($insurance, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $insurance = $this->insuranceService->updateInsurance($request->all(), $id);
            return Response::sendResponse($insurance, 'Registro actualizado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->insuranceService->deleteInsurance($id);
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
