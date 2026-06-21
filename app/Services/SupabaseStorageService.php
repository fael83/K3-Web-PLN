<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service untuk mengunggah & menghapus file pada Supabase Storage
 * melalui REST API Storage (menggunakan service role key di sisi server).
 */
class SupabaseStorageService
{
    protected string $url;
    protected string $serviceKey;
    protected string $bucket;

    public function __construct()
    {
        $this->url = rtrim((string) (config('supabase.url') ?: env('SUPABASE_URL')), '/');
        $this->serviceKey = (string) (config('supabase.service_role_key') ?: env('SUPABASE_SERVICE_ROLE_KEY') ?: env('SUPABASE_SERVICE_KEY'));
        $this->bucket = (string) (config('supabase.bucket') ?: env('SUPABASE_BUCKET', 'k3-files'));
    }

    /**
     * Unggah file ke Supabase Storage dan kembalikan public URL.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): ?string
    {
        try {
            if (! $this->isConfigured()) {
                Log::warning('Supabase Storage belum dikonfigurasi (SUPABASE_URL / SERVICE_ROLE_KEY kosong).');
                return null;
            }

            $extension = $file->getClientOriginalExtension() ?: $file->guessExtension();
            $path = trim($folder, '/') . '/' . Str::uuid() . '.' . $extension;

            $endpoint = "{$this->url}/storage/v1/object/{$this->bucket}/{$path}";
            $mimeType = $file->getMimeType() ?: 'application/octet-stream';
            $fileContent = file_get_contents($file->getRealPath());

            $response = Http::withoutVerifying()
                ->acceptJson()
                ->connectTimeout(15)
                ->timeout(120)
                ->retry(2, 1000, throw: false)
                ->withHeaders([
                    'apikey' => $this->serviceKey,
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                    'Content-Type' => $mimeType,
                    'x-upsert' => 'true',
                ])
                ->withBody($fileContent, $mimeType)
                ->post($endpoint);

            if ($response->successful()) {
                return $this->publicUrl($path);
            }

            Log::error('Gagal unggah ke Supabase Storage', [
                'status' => $response->status(),
                'body' => $response->body(),
                'endpoint' => $endpoint,
            ]);

            return null;
        } catch (ConnectionException $e) {
            Log::error('Koneksi ke Supabase Storage gagal', [
                'message' => $e->getMessage(),
            ]);
            return null;
        } catch (\Throwable $e) {
            Log::error('Exception saat upload ke Supabase Storage', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Hapus file dari Storage berdasarkan public URL yang tersimpan.
     */
    public function delete(?string $publicUrl): bool
    {
        try {
            if (! $publicUrl || ! $this->isConfigured()) {
                return false;
            }

            $marker = "/object/public/{$this->bucket}/";
            $pos = strpos($publicUrl, $marker);

            if ($pos === false) {
                return false;
            }

            $path = substr($publicUrl, $pos + strlen($marker));
            $endpoint = "{$this->url}/storage/v1/object/{$this->bucket}/{$path}";

            $response = Http::withoutVerifying()
                ->acceptJson()
                ->connectTimeout(15)
                ->timeout(60)
                ->retry(2, 1000, throw: false)
                ->withHeaders([
                    'apikey' => $this->serviceKey,
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                ])
                ->delete($endpoint);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Exception saat hapus file Supabase Storage', [
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Bangun public URL untuk path tertentu di dalam bucket.
     */
    public function publicUrl(string $path): string
    {
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/" . ltrim($path, '/');
    }

    public function isConfigured(): bool
    {
        return $this->url !== '' && $this->serviceKey !== '' && $this->bucket !== '';
    }
}