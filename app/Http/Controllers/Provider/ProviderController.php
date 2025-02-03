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
    protected $providerService;

    public function __construct(ProviderService $providerService)
    {
        $this->providerService = $providerService;
    }

    public function view()
    {
        return view('providers.provider');
    }

    public function index(Request $request)
    {
        try {
            $query = $this->providerService->getProvidersQuery($request->all());
            return renderDataTable(
                $query,
                $request,
                [],
                [
                    'providers.id',
                    'providers.name',
                    'providers.user',
                    'providers.address',
                    'providers.coverage_area',
                    'providers.contact_phone',
                    'providers.code_number',
                    'providers.code_country',
                    'providers.website',
                    'providers.email',
                    'providers.service_cost',
                    'providers.status',
                    'ec.name as country',
                    'ec.id as country_id',
                    'es.name as state',
                    'es.id as state_id',
                    'eci.name as city',
                    'eci.id as city_id',
                    'el.name as language',
                    'el.id as language_id',
                    'users.id as log_user_id',
                    'users.name as log_user_name',
                    'providers.created_at',
                    'providers.updated_at',
                    DB::raw('GROUP_CONCAT(CONCAT(e_provider.id, ":", e_provider.name) ORDER BY e_provider.name ASC SEPARATOR ";") as providers_name'),
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function byTypeCount()
    {
        try {
            $query = $this->providerService->getProvidersTypeQuery();
            $response = $query->get([
                DB::raw('CASE WHEN providers.status = 1 THEN "active" WHEN providers.status = 2 THEN "inactive" ELSE "unknown" END as type_name'),
                DB::raw('COUNT(providers.id) as count'),
            ]);
            return Response::sendResponse($response, __('messages.controllers.success.records_fetched_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function byProperty()
    {
        try {
            $data['status'] = 1;
            $query = $this->providerService->getProvidersQuery($data);
            $providers = $query->get(
                [
                    'providers.id',
                    'providers.name',
                ]
            );
            return Response::sendResponse($providers, __('messages.controllers.success.records_fetched_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function store(StoreProviderRequest $request)
    {
        try {
            $incident = $this->providerService->storeProvider($request->all());
            if ($incident == 'FALSE EMAIL') {
                return Response::sendError('FALSE EMAIL', 400);
            }
            return Response::sendResponse($incident, __('messages.controllers.success.record_created_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function update(UpdateProviderRequest $request, $id)
    {
        try {
            $incident = $this->providerService->updateProvider($request->all(), $id);
            return Response::sendResponse($incident, __('messages.controllers.success.record_updated_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->providerService->deleteProvider($id);
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
