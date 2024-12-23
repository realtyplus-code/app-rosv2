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
            return Response::sendResponse($property, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
