<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected $url;
    protected $key;
    protected $serviceKey;
    protected $storageUrl;
    protected $bucket;

    public function __construct()
    {
        $this->url = config('services.supabase.url');
        // Support both legacy and current config keys
        $this->key = config('services.supabase.anon_key');
        $this->serviceKey = config('services.supabase.service_role_key');
        $this->storageUrl = config('services.supabase.storage_url') ?? $this->url . '/storage/v1';
        $this->bucket = config('services.supabase.bucket', 'uploads');
    }

    /**
     * Upload file to Supabase Storage
     *
     * @param string $filePath Local file path
     * @param string $fileName File name in storage
     * @param string $folder Folder in bucket (optional)
     * @return array
     */
    public function uploadFile($filePath, $fileName, $folder = '')
    {
        try {
            $path = $folder ? trim($folder, '/') . '/' . $fileName : $fileName;
            
            $fileContent = file_get_contents($filePath);
            $mimeType = mime_content_type($filePath);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'Content-Type' => $mimeType,
            ])->withBody($fileContent, $mimeType)
              ->post("{$this->storageUrl}/object/{$this->bucket}/{$path}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->getPublicUrl($path),
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Upload failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase upload error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Upload base64 image to Supabase Storage
     *
     * @param string $base64Data Base64 encoded image data
     * @param string $fileName File name in storage
     * @param string $folder Folder in bucket (optional)
     * @return array
     */
    public function uploadBase64Image($base64Data, $fileName, $folder = '')
    {
        try {
            $path = $folder ? trim($folder, '/') . '/' . $fileName : $fileName;
            
            // Remove data:image/xxx;base64, prefix if exists
            $base64Data = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
            $imageContent = base64_decode($base64Data);
            
            if ($imageContent === false) {
                return [
                    'success' => false,
                    'error' => 'Invalid base64 data',
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'Content-Type' => 'image/png',
            ])->withBody($imageContent, 'image/png')
              ->post("{$this->storageUrl}/object/{$this->bucket}/{$path}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->getPublicUrl($path),
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Upload failed',
                'response' => $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Supabase upload base64 error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get public URL for a file
     *
     * @param string $path File path in bucket
     * @return string
     */
    public function getPublicUrl($path)
    {
        return "{$this->storageUrl}/object/public/{$this->bucket}/{$path}";
    }

    /**
     * Delete file from Supabase Storage
     *
     * @param string $path File path in bucket
     * @return array
     */
    public function deleteFile($path)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ])->delete("{$this->storageUrl}/object/{$this->bucket}/{$path}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'File deleted successfully',
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Delete failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase delete error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * List files in a folder
     *
     * @param string $folder Folder path
     * @return array
     */
    public function listFiles($folder = '')
    {
        try {
            $path = $folder ? trim($folder, '/') : '';
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ])->get("{$this->storageUrl}/object/list/{$this->bucket}", [
                'prefix' => $path,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'files' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'List failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase list error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Insert data to Supabase table
     *
     * @param string $table Table name
     * @param array $data Data to insert
     * @return array
     */
    public function insert($table, $data)
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->key,
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation',
            ])->post("{$this->url}/rest/v1/{$table}", $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Insert failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase insert error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Update data in Supabase table
     *
     * @param string $table Table name
     * @param array $filters Filters to identify records
     * @param array $data Data to update
     * @return array
     */
    public function update($table, $filters, $data)
    {
        try {
            $headers = [
                'apikey' => $this->key,
                'Authorization' => 'Bearer ' . $this->serviceKey,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation',
            ];
            // Build query string for filters (PostgREST expects ?id=eq.xxx)
            $url = "{$this->url}/rest/v1/{$table}";
            if (!empty($filters)) {
                // Build query manually to avoid encoding dots in operators (eq., like., etc.)
                $parts = [];
                foreach ($filters as $k => $v) {
                    // rawurlencode then restore '.' which is meaningful in PostgREST operators
                    $encoded = rawurlencode($v);
                    $encoded = str_replace('%2E', '.', $encoded);
                    $parts[] = $k . '=' . $encoded;
                }
                $query = implode('&', $parts);
                $url .= "?{$query}";
            }

            $response = Http::withHeaders($headers)->patch($url, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            // Improve error detail for easier debugging
            $body = $response->body();
            Log::warning('Supabase update failed', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $body,
            ]);

            $errorMsg = 'Update failed';
            try {
                $json = $response->json();
                if (is_array($json) && isset($json['message'])) {
                    $errorMsg = $json['message'];
                }
            } catch (\Exception $e) {
                // ignore
            }

            return [
                'success' => false,
                'error' => $errorMsg,
                'response_body' => $body,
                'status' => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Supabase update error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete data from Supabase table
     *
     * @param string $table Table name
     * @param array $filters Filters to identify records
     * @return array
     */
    public function delete($table, $filters)
    {
        try {
            $headers = [
                'apikey' => $this->key,
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ];
            // Build query string for filters
            $url = "{$this->url}/rest/v1/{$table}";
            if (!empty($filters)) {
                $query = http_build_query($filters);
                $url .= "?{$query}";
            }
            
            $response = Http::withHeaders($headers)->delete($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Delete failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase delete error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Select data from Supabase table
     *
     * @param string $table Table name
     * @param array $filters Filters (optional)
     * @return array
     */
    public function select($table, $filters = [])
    {
        try {
            $headers = [
                'apikey' => $this->key,
                'Authorization' => 'Bearer ' . $this->serviceKey,
            ];
            $response = Http::withHeaders($headers)->get("{$this->url}/rest/v1/{$table}", $filters);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Select failed',
            ];
        } catch (\Exception $e) {
            Log::error('Supabase select error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
