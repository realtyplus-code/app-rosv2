<?php

namespace App\Services\Insurance;

use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use App\Models\Insurance\Insurance;
use Illuminate\Support\Facades\Log;
use App\Services\Attachment\AttachmentService;
use App\Interfaces\Insurance\InsuranceRepositoryInterface;


class InsuranceService
{
    protected $fileService;
    protected $attachmentService;
    protected $insuranceRepository;
    private $listDocuments = ['document'];
    private $disk = 'disk_insurance';

    public function __construct(InsuranceRepositoryInterface $insuranceRepository,  AttachmentService $attachmentService, FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->attachmentService = $attachmentService;
        $this->insuranceRepository = $insuranceRepository;
    }

    public function getInsurancesQuery($data = [])
    {
        $query = Insurance::query()
            ->leftJoin('insurance_property', 'insurance_property.insurance_id', '=', 'insurances.id')
            ->leftJoin('properties', 'properties.id', '=', 'insurance_property.property_id')
            ->leftJoin('enum_options as e_ct', 'e_ct.id', '=', 'insurances.insurance_type_id')
            ->leftJoin('enum_options as ec', 'ec.id', '=', 'insurances.country');

        if (isset($data['property_id'])) {
            $query->where('insurance_property.property_id', $data['property_id']);
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

    public function getIncidentsTypeQuery()
    {
        $query = Insurance::query()
            ->leftJoin('enum_options as e_ct', 'e_ct.id', '=', 'insurances.insurance_type_id')
            ->groupBy('e_ct.name');

        return $query->distinct();
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
            if ($this->insuranceRepository->delete($id)) {
                $this->attachmentService->deleteByAttachable($id, Insurance::class, $this->disk);
            }
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

    public function addPdf($data)
    {
        foreach ($this->listDocuments as $key => $value) {
            if (isset($data['pdfs'][$key])) {
                $filePath = $this->fileService->saveFile($data['pdfs'][$key], 'pdf', $this->disk);
                $this->attachmentService->store([
                    'attachable_id' => $data['insurance_id'],
                    'attachable_type' => Insurance::class,
                    'file_path' => $filePath,
                    'file_type' => 'PDF',
                ]);
            }
        }
    }

    public function deletePdf($data)
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
}
