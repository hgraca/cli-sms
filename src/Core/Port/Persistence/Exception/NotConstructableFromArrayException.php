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
use Acme\PhpExtension\ConstructableFromArrayInterface;

final class NotConstructableFromArrayException extends AppRuntimeException
{
    public function __construct(string $fqcn)
    {
        parent::__construct(
            "The class $fqcn is not constructable from an array. "
            . 'It must implement interface ' . ConstructableFromArrayInterface::class . '.');
    }
}
