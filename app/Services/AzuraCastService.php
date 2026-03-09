<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AzuraCastService
{
    private string $baseUrl;
    private string $apiKey;
    private string $stationId;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.azuracast.url', env('AZURACAST_BASE_URL', '')), '/');
        $this->apiKey = config('services.azuracast.api_key', env('AZURACAST_API_KEY', ''));
        $this->stationId = config('services.azuracast.station_id', env('AZURACAST_STATION_ID', '1'));
    }

    public function isConfigured(): bool
    {
        return !empty($this->baseUrl) && !empty($this->apiKey);
    }

    public function getNowPlaying()
    {
        if (!$this->isConfigured()) {
            return null;
        }

        return Cache::remember('azuracast.nowplaying', 30, function () {
            try {
                $response = Http::withHeaders([
                    'X-API-Key' => $this->apiKey,
                ])->timeout(10)->get("{$this->baseUrl}/api/station/{$this->stationId}/nowplaying");

                if ($response->successful()) {
                    return $response->json();
                }

                Log::error('AzuraCast API error', ['status' => $response->status()]);
                return null;
            } catch (\Exception $e) {
                Log::error('AzuraCast API exception', ['error' => $e->getMessage()]);
                return null;
            }
        });
    }

    public function getStationInfo()
    {
        if (!$this->isConfigured()) {
            return null;
        }

        return Cache::remember('azuracast.station', 300, function () {
            try {
                $response = Http::withHeaders([
                    'X-API-Key' => $this->apiKey,
                ])->timeout(10)->get("{$this->baseUrl}/api/station/{$this->stationId}");

                return $response->successful() ? $response->json() : null;
            } catch (\Exception $e) {
                Log::error('AzuraCast station info error', ['error' => $e->getMessage()]);
                return null;
            }
        });
    }

    public function getStreamUrl(): ?string
    {
        $nowPlaying = $this->getNowPlaying();
        
        if ($nowPlaying) {
            if (isset($nowPlaying['station']['listen_url'])) {
                return $nowPlaying['station']['listen_url'];
            }

            if (isset($nowPlaying['station']['mounts'][0]['url'])) {
                return $nowPlaying['station']['mounts'][0]['url'];
            }
        }

        // Fallback to direct stream URL from settings
        $fallbackUrl = \App\Models\Setting::get('stream_url');
        if (!empty($fallbackUrl)) {
            return $fallbackUrl;
        }

        return null;
    }

    public function getRecentTracks(int $limit = 10): array
    {
        $nowPlaying = $this->getNowPlaying();
        
        if (!$nowPlaying || !isset($nowPlaying['song_history'])) {
            return [];
        }

        return array_slice($nowPlaying['song_history'], 0, $limit);
    }

    public function getListenersCount(): int
    {
        $nowPlaying = $this->getNowPlaying();
        
        return $nowPlaying['listeners']['total'] ?? 0;
    }

    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'AzuraCast not configured',
            ];
        }

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->timeout(10)->get("{$this->baseUrl}/api/station/{$this->stationId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection successful',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => "HTTP Error: {$response->status()}",
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
