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

namespace Acme\PhpExtension\Helper;

use Acme\PhpExtension\AbstractStaticClass;

final class ClassHelper extends AbstractStaticClass
{
    public static function extractCanonicalClassName(string $classFqcn): string
    {
        return mb_substr($classFqcn, mb_strrpos($classFqcn, '\\') + 1);
    }

    public static function extractCanonicalMethodName(string $methodFqcn): string
    {
        return mb_substr($methodFqcn, mb_strrpos($methodFqcn, '::') + 2);
    }
}
