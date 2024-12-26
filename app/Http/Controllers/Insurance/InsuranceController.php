<?php

namespace App\Http\Controllers\Insurance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

    public function view()
    {
        return view('insurances.insurance');
    }

    public function index(Request $request)
    {
        try {

            $role = Auth::user()->getRoleNames()[0];
            if ($role !== 'admin' && !$request->has('property_id')) {
                return Response::sendError('The property field is required for non-admin users', 400);
            }
            
            $query = $this->insuranceService->getInsurancesQuery($request->all());

            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'insurances.id',
                    'insurances.insurance_company',
                    'insurances.start_date',
                    'insurances.end_date',
                    'insurances.contact_person',
                    'insurances.contact_email',
                    'properties.id as property_id',
                    'properties.name as property_name',
                    'e_ct.name as coverage_name',
                    'e_ct.id as coverage_id',
                    'insurances.created_at',
                    'insurances.updated_at',
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
