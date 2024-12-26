<?php

namespace App\Services\Provider;

use Illuminate\Support\Facades\DB;
use App\Models\Provider\Provider;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Provider\ProviderRepositoryInterface;


class ProviderService
{
    protected $providerRepository;

    public function __construct(ProviderRepositoryInterface $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }

    public function getProvidersQuery($data)
    {
        $query = Provider::query();
        return $query->distinct();
    }

    public function storeProvider(array $data)
    {
        DB::beginTransaction();
        try {
            $provider = $this->providerRepository->create($data);
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
            $provider = $this->providerRepository->update($id, $data);
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
