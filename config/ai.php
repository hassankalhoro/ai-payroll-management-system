<?php

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI configuration
    |--------------------------------------------------------------------------
    | Powers the AI features: chat assistant, smart summaries, anomaly/fraud
    | detection, predictive payroll, organization-health analysis and OCR
    | document extraction. Keys are read from the environment so they can be
    | set as Railway secrets.
    */

    'key' => env('OPENAI_API_KEY'),

    'api_base' => env('OPENAI_API_BASE', 'https://api.openai.com/v1'),

    // Default text model for chat / insights / analysis.
    'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),

    // Vision-capable model used for OCR document extraction.
    'vision_model' => env('OPENAI_VISION_MODEL', 'gpt-4o'),

    // Request timeout in seconds (payroll analysis can be token-heavy).
    'timeout' => (int) env('OPENAI_TIMEOUT', 60),

    'temperature' => (float) env('OPENAI_TEMPERATURE', 0.2),

    'max_tokens' => (int) env('OPENAI_MAX_TOKENS', 1200),
];
