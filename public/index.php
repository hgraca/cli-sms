<?php
declare(strict_types=1);

use Acme\App\Infrastructure\Framework\Symfony\Kernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');


$defaultEnv = 'dev';
$appEnv = $_ENV['APP_ENV'] ?? $defaultEnv;

$defaultDebug = true;
$isDebug = $appEnv === 'prod'
    ? false
    : (bool) ($_ENV['APP_DEBUG'] ?? $defaultDebug);

if ($isDebug) {
    umask(0000);

    Debug::enable();
}

// Request::setTrustedProxies(['0.0.0.0/0'], Request::HEADER_FORWARDED);

$kernel = new Kernel($appEnv, $isDebug);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
