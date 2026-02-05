<?php

return [
    'paths' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://127.0.0.1:8003', 'http://localhost:5001', 'http://localhost:8080'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['Set-Cookie'],
    'max_age' => 0,
    'supports_credentials' => true,
];
