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
            
            $role = Auth::user()->getRoleNames()[0];
            if ($role !== 'admin' && !$request->has('incident_id')) {
                return Response::sendError(__('messages.controllers.warning.incident_field_required'), 400);
            }

            $query = $this->incidentActionService->getIncidentActionQuery($request->all());
            return renderDataTable(
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
                    'incident_actions.photo',
                    'incident_actions.photo1',
                    'incident_actions.photo2',
                    'incident_actions.photo3',
                    'incident_actions.document',
                    'incident_actions.document1',
                    'incident_actions.created_at',
                    'incident_actions.updated_at',
                ]
            );
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
            $pdf = $this->incidentActionService->addPdfIncident($request->all());
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
            $this->incidentActionService->deletePdfIncident($request->all());
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
