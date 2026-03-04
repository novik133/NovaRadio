<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AzuraCastService;
use Illuminate\Http\JsonResponse;

class NowPlayingController extends Controller
{
    public function __construct(private AzuraCastService $azuraCast) {}

    public function index(): JsonResponse
    {
        $nowPlaying = $this->azuraCast->getNowPlaying();

        if (!$nowPlaying) {
            return response()->json([
                'error' => 'Stream unavailable',
                'now_playing' => null,
                'listeners' => ['total' => 0],
                'song_history' => [],
            ]);
        }

        return response()->json($nowPlaying);
    }
}
