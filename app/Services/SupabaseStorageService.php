<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service untuk mengunggah & menghapus file pada Supabase Storage
 * melalui REST API Storage (menggunakan service role key di sisi server).
 *
 * Dokumentasi: https://supabase.com/docs/reference/api/introduction (Storage)
 */
class SupabaseStorageService
{
    protected string $url;
    protected string $serviceKey;
    protected string $bucket;

    public function __construct()
    {
        $this->url        = rtrim((string) config('supabase.url'), '/');
        $this->serviceKey = (string) config('supabase.service_role_key');
        $this->bucket     = (string) config('supabase.bucket');
    }

    /**
     * Unggah file ke Supabase Storage dan kembalikan public URL.
     *
     * @param  UploadedFile  $file   File dari request
     * @param  string        $folder Subfolder dalam bucket (mis. "apd", "denah", "incidents")
     * @return string|null   Public URL file, atau null bila gagal
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): ?string
    {
        if (! $this->isConfigured()) {
            Log::warning('Supabase Storage belum dikonfigurasi (SUPABASE_URL / SERVICE_ROLE_KEY kosong).');
            return null;
        }

        $extension = $file->getClientOriginalExtension() ?: $file->guessExtension();
        $path = trim($folder, '/').'/'.Str::uuid().'.'.$extension;

        $endpoint = "{$this->url}/storage/v1/object/{$this->bucket}/{$path}";

        $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$this->serviceKey,
                'Content-Type'  => $file->getMimeType(),
                'x-upsert'      => 'true',
            ])
            ->withBody(file_get_contents($file->getRealPath()), $file->getMimeType())
            ->post($endpoint);

        if ($response->successful()) {
            return $this->publicUrl($path);
        }

        Log::error('Gagal unggah ke Supabase Storage', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return null;
    }

    /**
     * Hapus file dari Storage berdasarkan public URL yang tersimpan.
     */
    public function delete(?string $publicUrl): bool
    {
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

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->serviceKey,
        ])->delete($endpoint);

        return $response->successful();
    }

    /**
     * Bangun public URL untuk path tertentu di dalam bucket.
     */
    public function publicUrl(string $path): string
    {
        return "{$this->url}/storage/v1/object/public/{$this->bucket}/".ltrim($path, '/');
    }

    public function isConfigured(): bool
    {
        return $this->url !== '' && $this->serviceKey !== '' && $this->bucket !== '';
    }
}
