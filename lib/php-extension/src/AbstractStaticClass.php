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

namespace Acme\PhpExtension;

/**
 * This class is just an utility class that helps us to remove duplication from the tests
 * and that's why it can't be instantiated.
 */
abstract class AbstractStaticClass
{
    protected function __construct()
    {
        // All methods should be static, so no need to instantiate any of the subclasses
    }
}
