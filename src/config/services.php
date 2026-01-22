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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    /*
    |--------------------------------------------------------------------------
    | AI Services Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for AI services including Ollama, OpenAI, and other
    | machine learning services used for forecasting and analysis.
    |
    */

    'ai_server' => [
        'url' => env('AI_SERVER_URL', 'http://localhost:11434/api/generate'),
        'model' => env('AI_MODEL', 'llama3.2'),
        'timeout' => env('AI_TIMEOUT', 60),
        'options' => [
            'temperature' => env('AI_TEMPERATURE', 0.3),
            'top_p' => env('AI_TOP_P', 0.9),
            'max_tokens' => env('AI_MAX_TOKENS', 2000),
        ],
    ],

];
