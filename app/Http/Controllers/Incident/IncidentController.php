<?php

namespace App\Http\Controllers\Incident;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Incident\IncidentService;

use App\Http\Requests\Incident\ValidatePdfRequest;
use App\Http\Requests\Incident\StoreIncidentRequest;
use App\Http\Requests\Incident\ValidatePhotoRequest;
use App\Http\Requests\Incident\UpdateIncidentRequest;
use App\Http\Controllers\ResponseController as Response;
use App\Models\Incident\Incident;

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

    public function viewKanban()
    {
        return view('incidents.incident_kanban');
    }

    public function index(Request $request)
    {
        try {
            $role = Auth::user()->getRoleNames()[0];
            $isNotOwnerOrTenant = !in_array($role, ['owner', 'tenant']);
            $query = $this->incidentService->getIncidentsQuery($request->all());
            $columns = [
                'incidents.id',
                'incidents.description',
                'properties.id as property_id',
                'properties.name as property_name',
                'properties.country as property_country',
                'incidents.report_date',
                'e_st.id as status_id',
                'e_st.name as status_name',
                'users.id as reported_by_id',
                'users.name as reported_by_name',
                'e_sev.id as priority_id',
                'e_sev.name as priority_name',
                'e_ct.id as type_id',
                'e_ct.name as type_name',
                'e_py.id as payer_id',
                'e_py.name as payer_name',
                'e_cur.id as currency_id',
                'e_cur.name as currency_name',
                'incidents.cost',
                'incidents.created_at',
                'incidents.updated_at'
            ];

            if($role == 'provider') {
                $columns[] = DB::raw('GROUP_CONCAT(DISTINCT CONCAT(providers.id, ":", providers.name, IF(providers.id != ' . Auth::id() . ', " (not_current_user)", "")) ORDER BY providers.name ASC SEPARATOR ";") as provider_name');
            } else {
                $columns[] = DB::raw('GROUP_CONCAT(DISTINCT CONCAT(providers.id, ":", providers.name) ORDER BY providers.name ASC SEPARATOR ";") as provider_name');
            }

            if ($isNotOwnerOrTenant) {
                $columns[] = DB::raw('COUNT(incident_actions.id) as incidents');
            } else {
                $columns[] = DB::raw("
                (SELECT COUNT(ia.id) 
                    FROM incident_actions ia
                    LEFT JOIN enum_options eat ON eat.id = ia.action_type_id
                    WHERE ia.incident_id = incidents.id AND eat.name != 'closure'
                ) as incidents
            ");
            }
            $response = renderDataTable($query, $request, [], $columns);
            $response = attachFilesToProperties($response, ['PHOTO' => 'photos', 'PDF' => 'document'], Incident::class, 'disk_incident');
            return $response;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function byTypeCount()
    {
        try {
            $query = $this->incidentService->getIncidentsTypeQuery();
            $response = $query->get([
                'e_st.name as type_name',
                DB::raw('COUNT(incidents.id) as count'),
            ]);
            return Response::sendResponse($response, __('messages.controllers.success.records_fetched_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function store(StoreIncidentRequest $request)
    {
        try {
            $incident = $this->incidentService->storeIncident($request->all());
            return Response::sendResponse($incident, __('messages.controllers.success.record_created_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function update(UpdateIncidentRequest $request, $id)
    {
        try {
            $type = $request->get('type') ?? null;
            switch ($type) {
                case 'status':
                    $incident = $this->incidentService->updateByStatusIncident($request->all(), $id);
                    break;
                default:
                    $incident = $this->incidentService->updateIncident($request->all(), $id);
                    break;
            }

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
            $incident = $this->incidentService->showIncident($id);
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
            $this->incidentService->deleteIncident($id);
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
            $photo = $this->incidentService->addPhotoIncident($request->all());
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
            $this->incidentService->deletePhotoIncident($request->all());
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
            $pdf = $this->incidentService->addPdf($request->all());
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
            $this->incidentService->deletePdf($request->all());
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
