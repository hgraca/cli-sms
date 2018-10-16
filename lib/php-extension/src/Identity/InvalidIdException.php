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

namespace Acme\PhpExtension\Identity;

use Acme\PhpExtension\Exception\AcmeLogicException;

final class InvalidIdException extends AcmeLogicException
{
    public function __construct($id)
    {
        parent::__construct(sprintf('Invalid id [%s]', $id));
    }
}
