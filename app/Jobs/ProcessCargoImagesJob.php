<?php

namespace App\Jobs;

use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessCargoImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected const DEFAULT_COLLECTION = 'cargo_images';
    protected const MAX_IMAGE_SIZE_BYTES = 8388608; // 8 MB

    public int $tries = 1;

    /**
     * @param array<int, array{type: string, path?: string, original_name?: string, url?: string}> $images
     */
    public function __construct(
        protected string $detailableType,
        protected int $detailableId,
        protected array $images,
        protected string $collection = self::DEFAULT_COLLECTION
    ) {
    }

    public function handle(): void
    {
        if (!class_exists($this->detailableType)) {
            return;
        }

        $model = $this->detailableType::query()->find($this->detailableId);

        if (!$model || !method_exists($model, 'addMedia')) {
            return;
        }

        foreach ($this->images as $image) {
            if (($image['type'] ?? null) === 'file') {
                $this->attachStoredFile($model, $image);
                continue;
            }

            if (($image['type'] ?? null) === 'url') {
                $this->attachUrlImage($model, $image);
            }
        }
    }

    protected function attachStoredFile(object $model, array $image): void
    {
        $path = $image['path'] ?? null;

        if (!$path) {
            return;
        }

        $absolutePath = Storage::path($path);

        if (!is_file($absolutePath)) {
            return;
        }

        try {
            $fileName = $this->resolveFileName($image['original_name'] ?? basename($absolutePath));

            $model->addMedia($absolutePath)
                ->usingFileName($fileName)
                ->toMediaCollection($this->collection);
        } catch (Throwable $exception) {
            Log::warning('Failed to attach uploaded cargo image', [
                'detailable_type' => $this->detailableType,
                'detailable_id' => $this->detailableId,
                'path' => $path,
                'error' => $exception->getMessage(),
            ]);
        } finally {
            Storage::delete($path);
        }
    }

    protected function attachUrlImage(object $model, array $image): void
    {
        $url = $image['url'] ?? null;

        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return;
        }

        $tempPath = null;

        try {
            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->get($url);

            if (!$response->successful()) {
                throw new \RuntimeException('URL did not return a successful response.');
            }

            $contentType = strtolower((string) $response->header('Content-Type'));
            if (!str_starts_with($contentType, 'image/')) {
                throw new \RuntimeException('URL content type is not an image.');
            }

            $binary = $response->body();
            if (strlen($binary) > self::MAX_IMAGE_SIZE_BYTES) {
                throw new \RuntimeException('Image size exceeds 8 MB limit.');
            }

            $extension = $this->resolveExtension($contentType, $url);
            if (!$extension) {
                throw new \RuntimeException('Unsupported image extension.');
            }

            $tempPath = tempnam(sys_get_temp_dir(), 'cargo_url_');
            if ($tempPath === false) {
                throw new \RuntimeException('Could not create temporary file.');
            }

            if (file_put_contents($tempPath, $binary) === false) {
                throw new \RuntimeException('Could not write downloaded image.');
            }

            $model->addMedia($tempPath)
                ->usingFileName(Str::uuid() . '.' . $extension)
                ->toMediaCollection($this->collection);
        } catch (Throwable $exception) {
            Log::warning('Skipped cargo URL image after retries', [
                'detailable_type' => $this->detailableType,
                'detailable_id' => $this->detailableId,
                'url' => $url,
                'error' => $exception->getMessage(),
            ]);
        } finally {
            if ($tempPath && is_file($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    protected function resolveFileName(string $originalName): string
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $extension = in_array($extension, ['jpeg', 'jpg', 'png', 'gif'], true) ? $extension : 'jpg';

        return Str::uuid() . '.' . $extension;
    }

    protected function resolveExtension(string $contentType, string $url): ?string
    {
        $normalized = strtolower(trim(explode(';', $contentType)[0]));

        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
        ];

        if (isset($mimeToExt[$normalized])) {
            return $mimeToExt[$normalized];
        }

        $urlExtension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));

        if (in_array($urlExtension, ['jpeg', 'jpg', 'png', 'gif'], true)) {
            return $urlExtension === 'jpeg' ? 'jpg' : $urlExtension;
        }

        return null;
    }
}
