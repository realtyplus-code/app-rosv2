<?php

namespace App\Services\InsuranceProperty;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Interfaces\InsuranceProperty\InsurancePropertyRepositoryInterface;

class InsurancePropertyService
{
    protected $insurancePropertyRepository;

    public function __construct(InsurancePropertyRepositoryInterface $insurancePropertyRepository)
    {
        $this->insurancePropertyRepository = $insurancePropertyRepository;
    }

    public function storeInsuranceProperty(array $data)
    {
        DB::beginTransaction();
        try {
            $insuranceProperty = $this->insurancePropertyRepository->create($data);
            DB::commit();
            return $insuranceProperty;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteInsuranceProperty($id)
    {
        try {
            $this->insurancePropertyRepository->delete($id);
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }
}
