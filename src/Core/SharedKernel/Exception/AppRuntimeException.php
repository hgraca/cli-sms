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

namespace Acme\App\Core\SharedKernel\Exception;

use Acme\PhpExtension\Exception\AcmeRuntimeException;

/**
 * This is the application RuntimeException, its the runtime exception that should used in our project code.
 * This is useful so we can catch and customise this projects exceptions.
 */
class AppRuntimeException extends AcmeRuntimeException
{
}
