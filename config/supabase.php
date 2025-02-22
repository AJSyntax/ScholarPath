<?php

return [
    'url' => env('SUPABASE_URL', ''),
    'key' => [
        'public' => env('SUPABASE_KEY_PUBLIC', ''),
        'secret' => env('SUPABASE_KEY_SECRET', ''),
    ],
    'project_id' => env('SUPABASE_PROJECT_ID', ''),
    'database' => [
        'host' => env('DB_HOST', ''),
        'port' => env('DB_PORT', '5432'),
        'database' => env('DB_DATABASE', ''),
        'username' => env('DB_USERNAME', ''),
        'password' => env('DB_PASSWORD', ''),
    ],
];
