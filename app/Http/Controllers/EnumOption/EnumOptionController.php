<?php

namespace App\Http\Controllers\EnumOption;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\EnumOption\EnumOptionService;
use App\Http\Controllers\ResponseController as Response;
use App\Http\Requests\EnumOption\StoreEnumOptionRequest;
use App\Http\Requests\EnumOption\UpdateEnumOptionRequest;

class EnumOptionController extends Controller
{
    protected $enumService;

    public function __construct(EnumOptionService $enumService)
    {
        $this->enumService = $enumService;
    }

    public function view()
    {
        return view('enum.enum');
    }

    public function index(Request $request)
    {
        try {
            $query = $this->enumService->getEnumsQuery();
            return renderDataTable(
                $query,
                $request,
                ['parent', 'brother_relation'],
                [
                    'enum_options.*',
                ]
            );
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function store(StoreEnumOptionRequest $request)
    {
        try {
            $enum = $this->enumService->storeEnum($request->all());
            return Response::sendResponse($enum, __('messages.controllers.success.record_created_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function update(UpdateEnumOptionRequest $request, $id)
    {
        try {
            $enum = $this->enumService->updateEnum($request->all(), $id);
            return Response::sendResponse($enum, __('messages.controllers.success.record_updated_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->enumService->deleteEnum($id);
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function listFathers()
    {
        try {
            $enums = $this->enumService->getByWhere([
                ['is_father', '=', true],
                ['name', '!=', 'master padre'],
            ]);
            return Response::sendResponse($enums, __('messages.controllers.success.records_fetched_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function listChildrens($name)
    {
        try {
            $enums = $this->enumService->listChildrens($name);
            return Response::sendResponse($enums, __('messages.controllers.success.records_fetched_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function getOptionById($id)
    {
        try {
            $enums = $this->enumService->getOptionById($id);
            return Response::sendResponse($enums, __('messages.controllers.success.record_fetched_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function getBrotherById($id)
    {
        try {
            $enums = $this->enumService->getBrotherById($id);
            return Response::sendResponse($enums, __('messages.controllers.success.records_fetched_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    
    public function getBrotherByIdAndCode($id, $code)
    {
        try {
            $enums = $this->enumService->getBrotherByIdAndCode($id, $code);
            return Response::sendResponse($enums, __('messages.controllers.success.records_fetched_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
