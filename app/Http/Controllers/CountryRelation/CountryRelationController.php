<?php

namespace App\Http\Controllers\CountryRelation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseController as Response;
use App\Services\CountryRelation\CountryRelationService;

class CountryRelationController extends Controller
{
    protected $countryRelationService;

    public function __construct(CountryRelationService $countryRelationService)
    {
        $this->countryRelationService = $countryRelationService;
    }

    public function view()
    {
        return view('country_relation.country_relation');
    }

    public function index(Request $request)
    {
        try {
            $query = $this->countryRelationService->getCountryRelationsQuery();
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'country_relations.*',
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $relation = $this->countryRelationService->storeCountryRelation($request->all());
            return Response::sendResponse($relation, __('messages.controllers.success.record_created_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $relation = $this->countryRelationService->updateCountryRelation($request->all(), $id);
            return Response::sendResponse($relation, __('messages.controllers.success.record_updated_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->countryRelationService->deleteCountryRelation($id);
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function getRelationByIdAndCode($id, $code)
    {
        try {
            $relation = $this->countryRelationService->getRelationByIdAndCode($id, $code);
            return Response::sendResponse($relation, __('messages.controllers.success.record_retrieved_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}