<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

trait ImageUpload
{
    /**
     * Upload an image file to a model's media collection
     *
     * @param mixed $model The model instance (must use Spatie MediaLibrary)
     * @param File|UploadedFile $file The image file to upload
     * @param string $collection The media collection name (default: 'default')
     * @return mixed The created media instance or null on failure
     */
    public function uploadImage($model, $file, string $collection = 'image')
    {
        if (!$file instanceof File) {
            return null;
        }

        return storeWebPWithSpatie($model, $file, $collection);
    }

    /**
     * Upload an image from request to a model's media collection
     *
     * @param mixed $model The model instance (must use Spatie MediaLibrary)
     * @param Request $request The HTTP request instance
     * @param string $fieldName The request field name containing the file
     * @param string $collection The media collection name (default: 'default')
     * @return mixed The created media instance or null on failure
     */
    public function uploadImageFromRequest($model, Request $request, string $fieldName = 'image', string $collection = 'image')
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        $file = $request->file($fieldName);
        
        if (!$file || !$file->isValid()) {
            return null;
        }

        return $this->uploadImage($model, $file, $collection);
    }

    /**
     * Upload multiple images from request to a model's media collection
     *
     * @param mixed $model The model instance (must use Spatie MediaLibrary)
     * @param Request $request The HTTP request instance
     * @param array $fieldNames Array of request field names containing files
     * @param string $collection The media collection name (default: 'default')
     * @return array Array of created media instances
     */
    public function uploadMultipleImagesFromRequest($model, Request $request, array $fieldNames, string $collection = 'default'): array
    {
        $uploadedMedia = [];

        foreach ($fieldNames as $fieldName) {
            $media = $this->uploadImageFromRequest($model, $request, $fieldName, $collection);
            if ($media) {
                $uploadedMedia[$fieldName] = $media;
            }
        }

        return $uploadedMedia;
    }

    /**
     * Upload image to storage without model association
     *
     * @param File|UploadedFile $file The image file to upload
     * @param string $directory Storage directory (default: 'uploads')
     * @return string|null The stored file path or null on failure
     */
    public function uploadImageToStorage($file, string $directory = 'uploads'): ?string
    {
        if (!$file instanceof File) {
            return null;
        }

        return storeWebP($file, $directory);
    }

    /**
     * Upload image from request to storage without model association
     *
     * @param Request $request The HTTP request instance
     * @param string $fieldName The request field name containing the file
     * @param string $directory Storage directory (default: 'uploads')
     * @return string|null The stored file path or null on failure
     */
    public function uploadImageFromRequestToStorage(Request $request, string $fieldName, string $directory = 'uploads'): ?string
    {
        if (!$request->hasFile($fieldName)) {
            return null;
        }

        $file = $request->file($fieldName);
        
        if (!$file || !$file->isValid()) {
            return null;
        }

        return $this->uploadImageToStorage($file, $directory);
    }
}