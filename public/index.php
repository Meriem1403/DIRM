<?php

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    // Configurer les trusted proxies pour Railway
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        Request::setTrustedProxies(
            ['*'], // Trust all proxies on Railway
            Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO
        );
    }
    
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
