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

namespace Acme\App\Core\SharedKernel\Exception;

use Acme\PhpExtension\Exception\AcmeLogicException;

/**
 * This is the application LogicException, its the logic exception that should used in our project code.
 * This is useful so we can catch and customise this projects exceptions.
 */
class AppLogicException extends AcmeLogicException
{
}
