<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Interfaces\User\UserRepositoryInterface;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsersQuery($role = null)
    {
        $consult = User::query();
        $consult->whereHas('roles', function ($query) use ($role) {
            $query->whereNotIn('name', ['admin']);
            if (!empty($role)) {
                $query->where('name', $role);
            }
        });
        return $consult;
    }

    public function storeUser(array $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->create($data);
            DB::commit();
            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function updateUser(array $data, $id)
    {
        try {
            $user = $this->userRepository->update($id, $data);
            DB::commit();
            return $user;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteUser($id)
    {
        try {
            $this->userRepository->delete($id);
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function showUser($id)
    {
        try {
            return $this->userRepository->findById($id);
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }
}
