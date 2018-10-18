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

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\AbstractStaticClass;

final class TypeHelper extends AbstractStaticClass
{
    public static function getType($subject): string
    {
        $type = \gettype($subject);
        switch ($type) {
            case 'object':
                return \get_class($subject);
            case 'array':
                // we assume all elements of the array are of the same type
                return (empty($subject) ? '' : self::getType(\reset($subject))) . '[]';
            default:
                return $type;
        }
    }
}
