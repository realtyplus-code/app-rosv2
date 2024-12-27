<?php

namespace App\Services\Provider;

use App\Models\Provider\Provider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Provider\ProviderRepositoryInterface;
use App\Interfaces\ProviderService\ProviderServiceRepositoryInterface;


class ProviderService
{
    protected $providerRepository;
    protected $providerServiceRepository;

    public function __construct(ProviderRepositoryInterface $providerRepository, ProviderServiceRepositoryInterface $providerServiceRepository)
    {
        $this->providerRepository = $providerRepository;
        $this->providerServiceRepository = $providerServiceRepository;
    }

    public function getProvidersQuery($data = null)
    {
        $query = Provider::query()
            ->leftJoin('provider_service', 'provider_service.provider_id', '=', 'providers.id')
            ->leftJoin('enum_options as e_provider', 'e_provider.id', '=', 'provider_service.service_id')
            ->groupBy(
                'providers.id',
                'providers.name',
                'providers.address',
                'providers.coverage_area',
                'providers.contact_phone',
                'providers.code_number',
                'providers.code_country',
                'providers.contact_email',
                'providers.service_cost',
                'providers.status',
            );

        if(isset($data['status']) && $data['status'] != null) {
            $query->where('providers.status', $data['status']);
        }

        return $query->distinct();
    }

    public function storeProvider(array $data)
    {
        DB::beginTransaction();
        try {
            $providers = $data['providers'];
            $provider = $this->providerRepository->create($data);
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
}
