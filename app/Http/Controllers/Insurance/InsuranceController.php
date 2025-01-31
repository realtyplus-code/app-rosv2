<?php

namespace App\Http\Controllers\Insurance;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Insurance\InsuranceService;
use App\Http\Requests\Insurance\ValidatePdfRequest;
use App\Http\Requests\Insurance\StoreInsuranceRequest;
use App\Http\Requests\Insurance\UpdateInsuranceRequest;
use App\Http\Controllers\ResponseController as Response;
use App\Models\Insurance\Insurance;

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
            $response = renderDataTable(
                $query,
                $request,
                [],
                [
                    'insurances.id',
                    'insurances.insurance_company',
                    'insurances.policy_number',
                    'insurances.start_date',
                    'insurances.end_date',
                    'insurances.contact_person',
                    'insurances.contact_email',
                    'ec.id as country_id',
                    'ec.name as country_name',
                    'insurances.position',
                    'insurances.phone',
                    'insurances.code_number',
                    'insurances.code_country',
                    'properties.name as property_name',
                    'e_ct.name as insurance_name',
                    'e_ct.id as insurance_id',
                    'insurances.created_at',
                    'insurances.updated_at',
                    'insurances.renewal_indicator',
                    'insurances.renewal_months',
                    'insurances.policy_amount',
                ]
            );
            $response = attachFilesToProperties($response, ['PHOTO' => 'photos', 'PDF' => 'document'], Insurance::class, 'disk_insurance');
            return $response;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function store(StoreInsuranceRequest $request)
    {
        try {
            $insurance = $this->insuranceService->storeInsurance($request->all());
            return Response::sendResponse($insurance, __('messages.controllers.success.record_created_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function show($id)
    {
        try {
            $insurance = $this->insuranceService->showInsurance($id);
            return Response::sendResponse($insurance, __('messages.controllers.success.record_fetched_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function update(UpdateInsuranceRequest $request, $id)
    {
        try {
            $insurance = $this->insuranceService->updateInsurance($request->all(), $id);
            return Response::sendResponse($insurance, __('messages.controllers.success.record_updated_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->insuranceService->deleteInsurance($id);
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function addPdf(ValidatePdfRequest $request)
    {
        try {
            $pdf = $this->insuranceService->addPdf($request->all());
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
            $this->insuranceService->deletePdf($request->all());
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
