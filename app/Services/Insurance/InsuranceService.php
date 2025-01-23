<?php

namespace App\Services\Insurance;

use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use App\Models\Insurance\Insurance;
use Illuminate\Support\Facades\Log;
use App\Interfaces\Insurance\InsuranceRepositoryInterface;


class InsuranceService
{
    protected $fileService;
    protected $insuranceRepository;
    private $listDocuments = ['document'];

    public function __construct(InsuranceRepositoryInterface $insuranceRepository, FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->insuranceRepository = $insuranceRepository;
    }

    public function getInsurancesQuery($data)
    {
        $query = Insurance::query()
            ->leftJoin('properties', 'properties.id', '=', 'insurances.property_id')
            ->leftJoin('enum_options as e_ct', 'e_ct.id', '=', 'insurances.insurance_type_id')
            ->leftJoin('enum_options as ec', 'ec.id', '=', 'insurances.country');

        if (isset($data['property_id'])) {
            $query->where('properties.id', $data['property_id']);
        }
    
        return $query->distinct();
    }

    public function storeInsurance(array $data)
    {
        DB::beginTransaction();
        try {
            $insurance = $this->insuranceRepository->create($data);
            DB::commit();
            return $insurance;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function updateInsurance(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $insurance = $this->insuranceRepository->update($id, $data);
            DB::commit();
            return $insurance;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteInsurance($id)
    {
        try {
            $this->insuranceRepository->delete($id);
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function showInsurance($id)
    {
        try {
            return $this->insuranceRepository->findById($id);
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function addPdfInsurance($data)
    {
        $flagColumn = null;
        $pdfs = $data['pdfs'];
        $insurance = $this->insuranceRepository->findById($data['insurance_id']);
        $columns = $this->listDocuments;
        foreach ($pdfs as $key => $pdf) {
            foreach ($columns as $column) {
                if (empty($insurance->{$column})) {
                    $insurance->{$column} = $this->fileService->saveFile($pdf, 'pdf', 'disk_insurance');
                    $flagColumn = $insurance->{$column};
                    $insurance->save();
                    break;
                }
            }
        }
        return $flagColumn;
    }

    public function deletePdfInsurance($data)
    {
        try {
            $insurance = $this->insuranceRepository->findById($data['insurance_id']);
            if ($data['type'] == 'document') {
                $this->fileService->deleteFile(cleanStorageUrl($insurance->document, '/storage_insurance/'), 'disk_insurance');
                $insurance->document = null;
            }
            $insurance->save();
            return $insurance;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }
}
