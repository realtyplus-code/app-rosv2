<?php

namespace App\Interfaces\Attachment;

interface AttachmentRepositoryInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    public function find($id);
    public function getByFileTypeAndAttachable($fileType, $attachableType);
    public function getByAttachable($attachableType, $attachableId);
    public function deleteByAttachable($attachableType, $attachableId);
}
