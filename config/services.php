<?php

return [
    'azuracast' => [
        'url' => env('AZURACAST_BASE_URL'),
        'api_key' => env('AZURACAST_API_KEY'),
        'station_id' => env('AZURACAST_STATION_ID', '1'),
    ],
    'github' => [
        'repo' => env('GITHUB_REPO', 'novik133/NovaRadia'),
        'token' => env('GITHUB_TOKEN'),
    ],
];
