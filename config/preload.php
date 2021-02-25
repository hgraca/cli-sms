<?php

declare(strict_types=1);

$preloadPath = dirname(__DIR__) . '/var/cache/prod/App_KernelProdContainer.preload.php';
if (file_exists($preloadPath)) {
    require $preloadPath;
}
