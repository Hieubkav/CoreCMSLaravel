<?php

namespace App\Traits;

trait HandlesFileUploadFields
{
    /**
     * Chuyển đổi file upload fields từ string thành array để Filament có thể hiển thị
     */
    protected function convertFileFieldsToArray(array $data, array $fileFields): array
    {
        foreach ($fileFields as $field) {
            if (!empty($data[$field])) {
                $data[$field] = [$data[$field]];
            } else {
                $data[$field] = [];
            }
        }
        
        return $data;
    }
    
    /**
     * Chuyển đổi file upload fields từ array về string để lưu vào database
     */
    protected function convertFileFieldsToString(array $data, array $fileFields): array
    {
        foreach ($fileFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $data[$field] = !empty($data[$field]) ? $data[$field][0] : null;
            }
        }
        
        return $data;
    }
    
    /**
     * Lấy danh sách các file upload fields mặc định
     */
    protected function getDefaultFileUploadFields(): array
    {
        return ['logo_link', 'favicon_link', 'placeholder_image', 'image_link', 'avatar_link', 'thumbnail_link'];
    }
}
