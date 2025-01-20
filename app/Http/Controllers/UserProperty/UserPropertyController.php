<?php

namespace App\Http\Controllers\UserProperty;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\UserProperty\UserPropertyService;
use App\Http\Controllers\ResponseController as Response;

class UserPropertyController extends Controller
{
    protected $userPropertyService;

    public function __construct(UserPropertyService $userPropertyService)
    {
        $this->userPropertyService = $userPropertyService;
    }

    public function showByUser($id)
    {
        try {
            $property = $this->userPropertyService->showProperty($id);
            return Response::sendResponse($property, __('messages.controllers.success.record_fetched_successfully'));
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
