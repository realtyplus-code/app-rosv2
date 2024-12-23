<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseController as Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function view(){
        return view('users.user');
    }

    public function index(Request $request)
    {
        try {
            $role = $request->query('role');
            $query = $this->userService->getUsersQuery($role);
            return renderDataTable(
                $query,
                $request,
                ['roles'],
                [
                    'users.id',
                    'users.name',
                    'users.phone',
                    'users.code_number',
                    'users.code_country',
                    'users.email',
                    'users.photo',
                    DB::raw('GROUP_CONCAT(CONCAT(p.id, ":", p.name) ORDER BY p.name ASC SEPARATOR ";") as property_name'),
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
            $user = $this->userService->storeUser($request->all());
            if($user == 'FALSE EMAIL'){
                return Response::sendError('FALSE EMAIL', 500);
            }
            return Response::sendResponse($user, 'Registro creado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userService->showUser($id);
            return Response::sendResponse($user, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $this->userService->updateUser($request->all(), $id);
            return Response::sendResponse($user, 'Registro actualizado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function addPhoto(Request $request)
    {
        try {
            $photo = $this->userService->addPhotoUser($request->all());
            return Response::sendResponse($photo, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }

    public function destroyPhoto(Request $request)
    {
        try {
            $this->userService->deletePhotoUser($request->all());
            return Response::sendResponse(true, 'Registro eliminado con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
