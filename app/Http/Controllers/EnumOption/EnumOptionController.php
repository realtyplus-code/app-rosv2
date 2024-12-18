<?php

namespace App\Http\Controllers\EnumOption;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\EnumOption\EnumOptionService;
use App\Http\Controllers\ResponseController as Response;

class EnumOptionController extends Controller
{
    protected $enumService;

    public function __construct(EnumOptionService $enumService)
    {
        $this->enumService = $enumService;
    }

    public function view()
    {
        return view('admin.enum');
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
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $enum = $this->enumService->storeEnum($request->all());
            return Response::sendResponse($enum, 'Registro creado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $enum = $this->enumService->updateEnum($request->all(), $id);
            return Response::sendResponse($enum, 'Registro actualizado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->enumService->deleteEnum($id);
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function listFathers()
    {
        try {
            $enums = $this->enumService->getByWhere([
                ['is_father', '=', true],
                ['name', '!=', 'master padre'],
            ]);
            return Response::sendResponse($enums, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function listChildrens($name)
    {
        try {
            $enums = $this->enumService->listChildrens($name);
            return Response::sendResponse($enums, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function getOptionById($id)
    {
        try {
            $enums = $this->enumService->getOptionById($id);
            return Response::sendResponse($enums, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function getBrotherById($id)
    {
        try {
            $enums = $this->enumService->getBrotherById($id);
            return Response::sendResponse($enums, 'Registros obtenidos con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
