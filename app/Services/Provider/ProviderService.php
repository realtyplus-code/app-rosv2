<?php

namespace App\Services\Provider;

use App\Mail\User\UserMail;
use App\Models\Provider\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Interfaces\Role\RoleRepositoryInterface;
use App\Interfaces\Provider\ProviderRepositoryInterface;
use App\Interfaces\ProviderService\ProviderServiceRepositoryInterface;

class ProviderService
{
    protected $providerRepository;
    protected $providerServiceRepository;
    protected $roleRepository;

    public function __construct(ProviderRepositoryInterface $providerRepository, RoleRepositoryInterface $roleRepository, ProviderServiceRepositoryInterface $providerServiceRepository)
    {
        $this->providerRepository = $providerRepository;
        $this->roleRepository = $roleRepository;
        $this->providerServiceRepository = $providerServiceRepository;
    }

    public function getProvidersQuery($data = null, $id = null)
    {
        $query = Provider::query()
            ->leftJoin('provider_service', 'provider_service.provider_id', '=', 'providers.id')
            ->leftJoin('enum_options as e_provider', 'e_provider.id', '=', 'provider_service.service_id')
            ->leftJoin('enum_options as ec', 'ec.id', '=', 'providers.country')
            ->leftJoin('enum_options as es', 'es.id', '=', 'providers.state')
            ->leftJoin('enum_options as eci', 'eci.id', '=', 'providers.city')
            ->leftJoin('enum_options as el', 'el.id', '=', 'providers.language_id')
            ->leftJoin('users', 'users.id', '=', 'providers.user_id');

        $this->getByUserRol($query);

        $query->groupBy([
            'providers.id',
            'providers.name',
            'providers.user',
            'providers.address',
            'providers.website',
            'providers.coverage_area',
            'providers.contact_phone',
            'providers.code_number',
            'providers.code_country',
            'providers.email',
            'providers.service_cost',
            'providers.status',
            'ec.name',
            'ec.id',
            'es.name',
            'es.id',
            'eci.name',
            'eci.id',
            'el.name',
            'el.id',
            'users.id',
            'users.name',
            'providers.created_at',
            'providers.updated_at',
        ]);

        if (isset($data['status']) && $data['status'] != null) {
            $query->where('providers.status', $data['status']);
        }

        if(!empty($id)){
            $query->where('providers.id', $id);
        }

        return $query->distinct();
    }

    private function getByUserRol(&$query)
    {
        switch (Auth::user()->getRoleNames()[0]) {
            case 'provider':
                $userId = Auth::id();
                $query->where('providers.id', $userId);
                break;
            default:
                break;
        }
    }

    public function storeProvider(array $data)
    {
        DB::beginTransaction();
        try {
            $providers = $data['providers'];
            $data['user_id'] = auth()->user()->id;
            $tmp_password = $data['password'];
            $data['password'] = Hash::make($data['password']);
            $provider = $this->providerRepository->create($data);
            foreach ($providers as $key => $value) {
                $this->providerServiceRepository->create([
                    'provider_id' => $provider->id,
                    'service_id' => $value,
                ]);
            }
            if ($provider) {
                $role = $this->roleRepository->findByName('provider');
                $provider->assignRole($role);
                $this->sendEmail($provider['email'], $provider, $tmp_password);
            }
            DB::commit();
            return $provider;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function getProvidersTypeQuery()
    {
        $query = Provider::query()
            ->groupBy('providers.status');

        return $query->distinct();
    }

    public function updateProvider(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $providers = $data['providers'];
            $provider = $this->providerRepository->update($id, $data);
            $this->providerServiceRepository->deleteByProvider($id);
            foreach ($providers as $key => $value) {
                $this->providerServiceRepository->create([
                    'provider_id' => $provider->id,
                    'service_id' => $value,
                ]);
            }
            DB::commit();
            return $provider;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteProvider($id)
    {
        try {
            $this->providerServiceRepository->deleteByProvider($id);
            $this->providerRepository->delete($id);
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function showProvider($id)
    {
        try {
            return $this->providerRepository->findById($id);
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
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
}
