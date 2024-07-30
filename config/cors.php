<?php
return [
    'paths' => ['*'],
    'allowed_methods' => ['GET, POST, PUT, OPTIONS'],
    'allowed_origins' => [env('ENDPOINT_A'),env('ENDPOINT_B'),env('ENDPOINT_C'),env('ENDPOINT_D'),],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Origin, Content-Type, X-Auth-Token , Cookie,Access-Control-Request-Headers','Authorization'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,

];