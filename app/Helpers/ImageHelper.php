<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\File\File; // Parent of UploadedFile

if (!function_exists('storeWebPWithSpatie')) {
    function storeWebPWithSpatie($model, File $file, string $collection = 'default'): mixed
    {
        if (!file_exists($file->getRealPath())) {
            return null;
        }

        try {
            $webp = Image::read($file->getRealPath())->encode(new WebpEncoder());
        } catch (\Exception $e) {
            return null;
        }

        $tempFilename = Str::uuid() . '.webp';
        $tempPath = storage_path('app/' . $tempFilename);

        if (file_put_contents($tempPath, $webp) === false || !file_exists($tempPath)) {
            return null;
        }

        $media = $model->addMedia($tempPath)
            ->usingFileName(Str::random(20) . '.webp')
            ->toMediaCollection($collection);

        @unlink($tempPath);

        return $media;
    }
}

if (!function_exists('storeWebP')) {
    function storeWebP(File $file, string $directory = 'uploads'): ?string
    {
        if (!file_exists($file->getRealPath())) {
            return null;
        }

        try {
            $webp = Image::read($file->getRealPath())->encode(new WebpEncoder());
        } catch (\Exception $e) {
            return null;
        }

        $filename = Str::random(40) . '.webp';
        $path = $directory . '/' . $filename;

        if (!Storage::disk('public')->put($path, (string) $webp)) {
            return null;
        }

        return $path;
    }
}

