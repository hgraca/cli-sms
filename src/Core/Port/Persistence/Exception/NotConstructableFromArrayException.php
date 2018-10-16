<?php

declare(strict_types=1);

/*
 * This file is part of the CLI SMS application,
 * which is created on top of the Explicit Architecture POC,
 * which is created on top of the Symfony Demo application.
 *
 * This project is used for on-boarding of new developers into Werkspot dev teams.
 *
 * (c) Werkspot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
