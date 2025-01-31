<?php

namespace App\Http\Controllers\InsuranceProperty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\InsuranceProperty\InsurancePropertyService;
use App\Http\Controllers\ResponseController as Response;

class InsurancePropertyController extends Controller
{
    protected $insurancePropertyService;

    public function __construct(InsurancePropertyService $insurancePropertyService)
    {
        $this->insurancePropertyService = $insurancePropertyService;
    }

    public function store(Request $request)
    {
        try {
            $insuranceProperty = $this->insurancePropertyService->storeInsuranceProperty($request->all());
            return Response::sendResponse($insuranceProperty, __('messages.controllers.success.record_created_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->insurancePropertyService->deleteInsuranceProperty($id);
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
