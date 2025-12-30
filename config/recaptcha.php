<?php

return [
    'api_site_key'                  => env('RECAPTCHA_SITE_KEY', ''),
    'api_secret_key'                => env('RECAPTCHA_SECRET_KEY', ''),
    'version'                       => 'v2', // v2, invisible, v3
    'curl_timeout'                  => 10,
    'skip_ip'                       => [],
    'default_validation_route'      => 'biscolab-recaptcha/validate',
    'default_token_parameter_name' => 'token',
    'default_language'             => 'id',
];
