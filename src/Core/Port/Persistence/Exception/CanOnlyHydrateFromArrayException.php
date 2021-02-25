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

namespace Acme\App\Core\Port\Persistence\Exception;

use Acme\App\Core\SharedKernel\Exception\AppRuntimeException;

final class CanOnlyHydrateFromArrayException extends AppRuntimeException
{
    public function __construct($item)
    {
        parent::__construct('Can only hydrate to object from an array, \'' . $this->getType($item) . '\' given.');
    }

    private function getType($item): string
    {
        $type = \gettype($item);
        if ($type === 'object') {
            return \get_class($item);
        }

        return $type;
    }
}
