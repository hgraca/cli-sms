<?php

declare(strict_types=1);

/*
 * This file is part of the CLI SMS application,
 * which is created on top of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * This project is used in a workshop to explain Architecture patterns.
 *
 * Most of it authored by Herberto Graca.
 */

namespace Acme\App\Core\Port\EventDispatcher;

/**
 * The events will not be immediately dispatched, they will be buffered and flushed at the end of the HTTP request.
 */
interface BufferedEventDispatcherInterface extends EventDispatcherInterface
{
    public function flush(): void;

    public function reset(): void;
}
