<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'supabase' => [
        'url' => env('SUPABASE_URL'),
        'anon_key' => env('SUPABASE_ANON_KEY'),
        'service_role_key' => env('SUPABASE_SERVICE_ROLE_KEY'),
        'bucket' => env('SUPABASE_STORAGE_BUCKET', 'uploads'),
        'connect_timeout' => env('SUPABASE_CONNECT_TIMEOUT', 3),
        'timeout' => env('SUPABASE_TIMEOUT', 8),
        'retry_times' => env('SUPABASE_RETRY_TIMES', 1),
        'retry_sleep_ms' => env('SUPABASE_RETRY_SLEEP_MS', 150),
        'sync_enabled' => env('SUPABASE_SYNC_ENABLED', true),
        'sidang_mode' => env('SIDANG_MODE', false),
        'validation_max_rows' => env('VALIDATION_MAX_ROWS', 80),
        'timing_log_enabled' => env('TIMING_LOG_ENABLED', false),
    ],



];
