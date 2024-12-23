<?php

namespace App\Http\Controllers\Role;

use App\Services\Role\RoleService;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseController as Response;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        try {
            $roles = $this->roleService->getRolsQuery()->toArray();
            return Response::sendResponse($roles, 'Registro obtenido con exito.');
        } catch (\Exception $ex) {
            return Response::sendError('Ocurrio un error inesperado al intentar procesar la solicitud', 500);
        }
    }
}
