<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\ValidatePhotoRequest;
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
                ['roles', 'userRelations'],
                [
                    'users.id',
                    'users.name',
                    'users.phone',
                    'users.address',
                    'ec.id as country_id',
                    'es.id as state_id',
                    'eci.id as city_id',
                    'ec.name as country_name',
                    'es.name as state_name',
                    'eci.name as city_name',
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
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->userService->storeUser($request->all());
            if($user == 'FALSE EMAIL'){
                return Response::sendError('FALSE EMAIL', 400);
            }
            return Response::sendResponse($user, __('messages.controllers.success.record_created_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userService->showUser($id);
            return Response::sendResponse($user, __('messages.controllers.success.record_fetched_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $request->merge(['id' => $id]);
            $user = $this->userService->updateUser($request->all(), $id);
            return Response::sendResponse($user, __('messages.controllers.success.record_updated_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->deleteUser($id);
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
            $photo = $this->userService->addPhotoUser($request->all());
            return Response::sendResponse($photo, 'Foto agregada con exito.');
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function destroyPhoto(Request $request)
    {
        try {
            $this->userService->deletePhotoUser($request->all());
            return Response::sendResponse(true, __('messages.controllers.success.record_deleted_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }

    public function getUsersByRole($roleName)
    {
        try {
            $users = $this->userService->getUsersByRole($roleName);
            return Response::sendResponse($users, __('messages.controllers.success.record_fetched_successfully'));
        } catch (\Exception $ex) {
            return Response::sendError(__('messages.controllers.error.unexpected_error'), 500);
        }
    }
}
