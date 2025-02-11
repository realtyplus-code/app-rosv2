<?php

namespace App\Services\User;

use App\Models\User;
use App\Mail\User\UserMail;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Services\Attachment\AttachmentService;
use App\Interfaces\Role\RoleRepositoryInterface;
use App\Interfaces\User\UserRepositoryInterface;
use App\Interfaces\UserRelation\UserRelationRepositoryInterface;

class UserService
{
    protected $userRepository;
    protected $roleRepository;
    protected $fileService;
    protected $userRelationRepository;
    protected $attachmentService;
    private $disk = 'disk_user';
    private $listPhotos = ['photo'];

    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository,
        UserRelationRepositoryInterface $userRelationRepository,
        AttachmentService $attachmentService,
        FileService $fileService
    ) {
        $this->fileService = $fileService;
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->attachmentService = $attachmentService;
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
            ->leftJoin('enum_options as el', 'el.id', '=', 'users.language_id');

        if (Auth::user()->getRoleNames()[0] != 'global_manager') {
            $this->getByUserRol($consult, $role);
        }

        $consult->groupBy([
            'users.id',
            'users.name',
            'users.phone',
            'ec.id',
            'es.id',
            'eci.id',
            'el.id',
            'ec.name',
            'es.name',
            'eci.name',
            'el.name',
            'users.code_number',
            'users.code_country',
            'users.email',
            'users.address',
            'users.user',
        ]);

        return $consult;
    }

    private function getByUserRol(&$query, $role)
    {
        $userId = Auth::id();
        if (empty($role)) {
            $role = Auth::user()->getRoleNames()[0];
        }
        switch ($role) {
            case 'owner':
            case 'tenant':
                $query->where('users.id', $userId);
                break;
            case 'ros_client':
            case 'ros_client_manager':
                $query->whereIn('users.id', function ($query) use ($userId) {
                    $query->select('users_relations.user_id')
                        ->from('users_relations')
                        ->where('users_relations.user_id_related', $userId);
                })->orWhere('users.id', $userId);
                break;
            default:
                break;
        }
    }

    public function storeUser(array $data)
    {
        DB::beginTransaction();
        try {
            $photos = $data['photos'];
            unset($data['photos']);
            $data['tmp_password'] = $data['password'];
            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepository->create($data);
            $role = $this->roleRepository->findById($data['role']);
            $this->assignAttachments($user, $photos);

            if (isset($data['ros_clients'])) {
                foreach ($data['ros_clients'] as $client) {
                    $this->userRelationRepository->create([
                        'user_id_related' => $user['id'],
                        'type' => 'CLIENT',
                        'user_id' => $client
                    ]);
                }
            }

            $senEmail = false;
            if ($role && $user) {
                $user->assignRole($role);
                if ($user->save()) {
                    $senEmail = $this->sendEmail($user['email'], $user, $data['tmp_password']);
                }
            }
            if (!empty($user) && $user->email && !$senEmail) {
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
            if (isset($data['ros_clients'])) {
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
            if ($this->userRepository->delete($id)) {
                $this->attachmentService->deleteByAttachable($id, User::class, $this->disk);
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

    private function assignAttachments(&$user, $photos)
    {
        foreach ($this->listPhotos as $key => $value) {
            if (isset($photos[$key])) {
                $filePath = $this->fileService->saveFile($photos[$key], 'photo', $this->disk);
                $this->attachmentService->store([
                    'attachable_id' => $user->id,
                    'attachable_type' => User::class,
                    'file_path' => $filePath,
                    'file_type' => 'PHOTO',
                ]);
            }
        }
    }

    private function unassignPhoto($user)
    {
        $this->fileService->deleteFile(cleanStorageUrl($user->photo, '/storage_user/'), 'disk_user');
    }

    private function sendEmail($to, $details, $tmp_password)
    {
        try {
            $details['password'] = $tmp_password;
            Mail::to($to)->send(new UserMail($details));
            return true;
        } catch (\Exception $ex) {
            Log::debug($ex->getMessage());
            return false;
        }
    }

    public function addPhotoUser($data)
    {
        $filePath = $this->fileService->saveFile($data['photo'], 'photo', $this->disk);
        $attachment = $this->attachmentService->store([
            'attachable_id' => $data['user_id'],
            'attachable_type' => User::class,
            'file_path' => $filePath,
            'file_type' => 'PHOTO',
        ]);
        $attachment->file_path = Storage::disk($this->disk)->url($attachment->file_path);
        return $attachment;
    }

    public function deletePhotoUser($data)
    {
        try {
            $attachment = $this->attachmentService->getById($data['attachment_id']);
            if ($this->fileService->deleteFile($attachment->file_path, $this->disk)) {
                $this->attachmentService->delete($data['attachment_id']);
            }
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function getUsersByRole($roleName)
    {
        return User::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->get();
    }
}
