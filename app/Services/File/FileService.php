<?php

namespace App\Services\File;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function saveFile(UploadedFile $file, $subfolder = null, $disk = 'disk_property')
    {
        $filename = time() . '_' . rand(1, 1000) . $file->getClientOriginalName();
        $path = $this->buildPath($subfolder, $filename);
        $file->storeAs('', $path, $disk);
        return $path;
    }

    public function deleteFile($path, $disk = 'disk_property')
    {
        if (empty($path)) {
            return false;
        }

        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
            return true;
        }
        return false;
    }

    public function getFileUrl($path, $disk = 'disk_property')
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->url($path);
        }
        return null;
    }

    private function buildPath($subfolder, $filename)
    {
        $path = $subfolder . '/';
        $path .= $filename;
        return $path;
    }
}
