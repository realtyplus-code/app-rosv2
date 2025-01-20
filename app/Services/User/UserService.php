<?php

namespace App\Services\User;

use App\Models\User;
use App\Mail\User\UserMail;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Interfaces\Role\RoleRepositoryInterface;
use App\Interfaces\User\UserRepositoryInterface;
use App\Interfaces\UserRelation\UserRelationRepositoryInterface;

class UserService
{
    protected $userRepository;
    protected $roleRepository;
    protected $fileService;
    protected $userRelationRepository;
    private $listPhotos = ['photo'];

    public function __construct(UserRepositoryInterface $userRepository, RoleRepositoryInterface $roleRepository, UserRelationRepositoryInterface $userRelationRepository, FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->userRelationRepository = $userRelationRepository;
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

        $consult->leftJoin('user_properties as up', 'up.user_id', '=', 'users.id')
            ->leftJoin('properties as p', 'p.id', '=', 'up.property_id')
            ->leftJoin('enum_options as ec', 'ec.id', '=', 'users.country')
            ->leftJoin('enum_options as es', 'es.id', '=', 'users.state')
            ->leftJoin('enum_options as eci', 'eci.id', '=', 'users.city')
            ->groupBy([
                'users.id',
                'users.name',
                'users.phone',
                'ec.id',
                'es.id',
                'eci.id',
                'ec.name',
                'es.name',
                'eci.name',
                'users.code_number',
                'users.code_country',
                'users.email',
                'users.photo',
                'users.address'
            ]);

        return $consult;
    }

    public function storeUser(array $data)
    {
        DB::beginTransaction();
        try {
            $photos = $data['photos'];
            unset($data['photos']);
            $user = $this->userRepository->create($data);
            $role = $this->roleRepository->findById($data['role']);
            $this->assignedPhoto($user, $photos);

            foreach ($data['ros_clients'] as $client) {
                $this->userRelationRepository->create([
                    'user_id_related' => $user['id'],
                    'type' => 'CLIENT',
                    'user_id' => $client
                ]);
            }

            if ($role && $user) {
                $user->assignRole($role);
                if ($user->save()) {
                    $this->sendEmail($user['email'], $user);
                }
            }
            if (!empty($user) && $user->email && !$this->sendEmail($user['email'], $user)) {
                $this->unassignPhoto($user);
                DB::rollBack();
                return 'FALSE EMAIL';
            }
            DB::commit();
            return $user;
        } catch (\Exception $ex) {
            $this->unassignPhoto($user);
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
            if (isset($data['role'])) {
                $user->roles()->detach();
                $role = $this->roleRepository->findById($data['role']);
                $user->assignRole($role);
            }
            if(isset($data['ros_clients'])){
                $this->userRelationRepository->deleteByManagement($id);
                foreach ($data['ros_clients'] as $client) {
                    $this->userRelationRepository->create([
                        'user_id_related' => $id,
                        'type' => 'CLIENT',
                        'user_id' => $client
                    ]);
                }
            }
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
            $currentUser = User::find($id);
            if ($this->fileService->deleteFile(cleanStorageUrl($currentUser->photo, '/storage_user/'), 'disk_user')) {
                $this->userRepository->delete($id);
            }
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

    private function assignedPhoto(&$user, $photos)
    {
        if (isset($photos[0])) {
            $user->photo = $this->fileService->saveFile($photos[0], 'photo', 'disk_user');
        }
    }

    private function unassignPhoto($user)
    {
        $this->fileService->deleteFile(cleanStorageUrl($user->photo, '/storage_user/'), 'disk_user');
    }

    private function sendEmail($to, $details)
    {
        try {
            Mail::to($to)->send(new UserMail($details));
            return true;
        } catch (\Exception $ex) {
            Log::debug($ex->getMessage());
            return false;
        }
    }

    public function addPhotoUser($data)
    {
        $flagColumn = null;
        $user = $this->userRepository->findById($data['user_id']);
        $columns = $this->listPhotos;
        foreach ($columns as $column) {
            if (empty($user->{$column})) {
                $user->{$column} = $this->fileService->saveFile($data['photo'], 'photo', 'disk_user');
                $flagColumn = $user->{$column};
                $user->save();
                break;
            }
        }
        return $flagColumn;
    }

    public function deletePhotoUser($data)
    {
        try {
            $matchingColumn = $this->findPhotoColumn($data['user_id'], $data['photo']);
            if ($matchingColumn) {
                if ($this->fileService->deleteFile(cleanStorageUrl($matchingColumn[1][$matchingColumn[0]], '/storage_user/'), 'disk_user')) {
                    User::where('id', $data['user_id'])->update([
                        $matchingColumn[0] => null
                    ]);
                }
            }
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    private function findPhotoColumn($id, $photo)
    {
        $filename = basename($photo);
        $user = User::where('id', $id)
            ->where(function ($query) use ($filename) {
                $query->where('photo', 'LIKE', "%$filename%");
            })
            ->first();

        if (!$user) {
            return null;
        }

        $columns = $this->listPhotos;
        foreach ($columns as $column) {
            if ($user->$column == $photo) {
                return [$column, $user->toArray()];
            }
        }
        return null;
    }

    public function getUsersByRole($roleName)
    {
        return User::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->get();
    }
}
