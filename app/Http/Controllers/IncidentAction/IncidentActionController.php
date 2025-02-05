<?php

namespace App\Http\Controllers\IncidentAction;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\IncidentAction\IncidentActionService;
use App\Http\Controllers\ResponseController as Response;
use App\Http\Requests\IncidentAction\ValidatePdfRequest;
use App\Http\Requests\IncidentAction\ValidatePhotoRequest;
use App\Http\Requests\IncidentAction\StoreIncidentActionRequest;
use App\Http\Requests\IncidentAction\UpdateIncidentActionRequest;
use App\Models\IncidentAction\IncidentAction;

class IncidentActionController extends Controller
{
    protected $incidentActionService;

    public function __construct(IncidentActionService $incidentActionService)
    {
        $this->incidentActionService = $incidentActionService;
    }

    public function view()
    {
        return view('incident_actions.incident_action');
    }

    public function index(Request $request)
    {
        try {
            $query = $this->incidentActionService->getIncidentActionQuery($request->all());
            $response = renderDataTable(
                $query,
                $request,
                [],
                [
                    'incident_actions.id',
                    'users.id as user_id',
                    'users.name as user_name',
                    'providers.id as provider_id',
                    'providers.name as provider_name',
                    'incident_actions.responsible_user_type as responsible_type',
                    'incidents.id as incident_id',
                    'incidents.description as incident_name',
                    'incident_actions.action_description',
                    'incident_actions.action_date',
                    'incident_actions.action_cost',
                    'incident_actions.created_at',
                    'incident_actions.updated_at',
                    'eat.id as action_type_id',
                    'eat.name as action_type_name',
                    'es.id as status_id',
                    'es.name as status_name',
                    'ec.id as currency_id',
                    'ec.name as currency_name',
                    'user_log.id as log_user_id',
                    'user_log.name as log_user_name',
                ]
            );
            $response = attachFilesToProperties($response, ['PHOTO' => 'photos', 'PDF' => 'document'], IncidentAction::class, 'disk_incident_action');
            return $response;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function store(StoreIncidentActionRequest $request)
    {
        try {
            $incident = $this->incidentActionService->storeIncident($request->all());
            return Response::sendResponse($incident, __('messages.controllers.success.record_created_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function update(UpdateIncidentActionRequest $request, $id)
    {
        try {

            $incident = $this->incidentActionService->updateIncident($request->all(), $id);
            return Response::sendResponse($incident, __('messages.controllers.success.record_updated_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function show($id)
    {
        try {
            $incident = $this->incidentActionService->showIncident($id);
            return Response::sendResponse($incident, __('messages.controllers.success.record_fetched_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->incidentActionService->deleteIncident($id);
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
            $photo = $this->incidentActionService->addPhotoIncident($request->all());
            return Response::sendResponse($photo, __('messages.controllers.success.record_added_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroyPhoto(Request $request)
    {
        try {
            $this->incidentActionService->deletePhotoIncident($request->all());
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
            $pdf = $this->incidentActionService->addPdf($request->all());
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
            $this->incidentActionService->deletePdf($request->all());
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
