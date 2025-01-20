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
            return Response::sendResponse($roles, __('messages.controllers.success.record_fetched_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
