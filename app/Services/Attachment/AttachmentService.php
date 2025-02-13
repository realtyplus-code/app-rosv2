<?php

namespace App\Services\Attachment;

use Faker\Core\File;
use App\Services\File\FileService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\Attachment\AttachmentRepositoryInterface;

class AttachmentService
{
    protected $attachmentRepository;
    protected $fileService;

    public function __construct(AttachmentRepositoryInterface $attachmentRepository, FileService $fileService)
    {
        $this->fileService = $fileService;
        $this->attachmentRepository = $attachmentRepository;
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            $item = $this->attachmentRepository->create($data);
            DB::commit();
            return $item;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function update(array $data, $id)
    {
        DB::beginTransaction();
        try {
            $item = $this->attachmentRepository->update($id, $data);
            DB::commit();
            return $item;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function delete($id)
    {
        try {   
            $this->attachmentRepository->delete($id);
            return true;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function deleteByAttachable($attachableType, $attachableId, $disk)
    {
        DB::beginTransaction();
        try {
            $response = $this->attachmentRepository->getByAttachable($attachableId, $attachableType);
            foreach ($response as $key => $value) {
                if(isset($value['file_path']) && !empty($value['file_path'])) {
                    $this->fileService->deleteFile($value['file_path'], $disk);
                }
            }
            $this->attachmentRepository->deleteByAttachable($attachableId, $attachableType);
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function getByFileTypeAndAttachable($fileType, $attachableType, $disk)
    {
        try {
            $response = $this->attachmentRepository->getByFileTypeAndAttachable($fileType, $attachableType);
            foreach ($response as $key => $value) {
                if(isset($value->file_path) && !empty($value->file_path)) {
                    $filePath = Storage::disk($disk)->url($value->file_path);
                    $response[$key]->file_path = $filePath;
                }
            }
            return $response;
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }

    public function getById($id)
    {
        try {
            return $this->attachmentRepository->find($id);
        } catch (\Exception $ex) {
            Log::info($ex->getLine());
            Log::info($ex->getMessage());
            throw $ex;
        }
    }
}
